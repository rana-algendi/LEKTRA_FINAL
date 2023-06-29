import pickle
import numpy as np
import cv2
from skimage.feature import hog
from skimage import feature
from torchvision import transforms
from PIL import Image

model = pickle.load(open('model_93.pkl', 'rb'))
eye_cascade = cv2.CascadeClassifier('haarcascade_eye.xml')


def predict(img):
    results = []
    prediction = 'Normal'
    image = Image.open(img)
    image = np.asarray(image)
    rgbimg = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)

    eyes = eye_cascade.detectMultiScale(rgbimg, scaleFactor=1.1, minNeighbors=15)
    try:
        for (ex, ey, ew, eh) in eyes:
            cropped_image = image[ey:ey + eh, ex:ex + ew]
            results.append(read_images(cropped_image))

        for r in results:
            if (r == 'retino'):
                prediction = 'Leukocoria'
    except:
        prediction = "Can't detect eyes from this picture"

    return prediction


class LocalBinaryPatterns:
    def __init__(self, numPoints, radius):
        # store the number of points and radius
        self.numPoints = numPoints
        self.radius = radius

    def describe(self, image, eps=1e-7):
        lbp = feature.local_binary_pattern(image, self.numPoints,
                                           self.radius, method="uniform")
        (hist, _) = np.histogram(lbp.ravel(),
                                 bins=np.arange(0, self.numPoints + 3),
                                 range=(0, self.numPoints + 2))

        hist = hist.astype("float")
        hist /= (hist.sum() + eps)

        return hist


def read_images(img):
    desc = LocalBinaryPatterns(24, 8)
    resized_img = cv2.resize(img, (100, 50))
    test_img = cv2.cvtColor(resized_img, cv2.COLOR_BGR2RGB)
    test_img_gray = cv2.cvtColor(test_img, cv2.COLOR_RGB2GRAY)
    test_img_thresh = cv2.adaptiveThreshold(test_img_gray, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
                                            cv2.THRESH_BINARY_INV, 199, 5)
    cnts = cv2.findContours(test_img_thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    cnts = cnts[0] if len(cnts) == 2 else cnts[1]
    cnts = sorted(cnts, key=cv2.contourArea, reverse=True)
    for c in cnts:
        x, y, w, h = cv2.boundingRect(c)
        test_img_ROI = resized_img[y:y + h, x:x + w]
        break
    test_img_ROI_resize_gray = cv2.cvtColor(test_img_ROI, cv2.COLOR_RGB2GRAY)
    hist = desc.describe(test_img_ROI_resize_gray)
    fd, hog_image = hog(resized_img, orientations=8, pixels_per_cell=(4, 4),
                        cells_per_block=(2, 2), visualize=True, channel_axis=-1)
    hog_image = np.array(hog_image).flatten()
    f_vector_concat = np.hstack([hog_image, hist])
    prediction = model.predict([f_vector_concat])
    return prediction
