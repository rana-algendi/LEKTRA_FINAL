from flask import *
from flaskbackend import *


@app.route('/success/', methods=['POST'])
def success():
    if request.method == 'POST':
        f = request.files['file']
        result = predict_image(f)
        return str(result)  # Modify the response as per your requirements


if __name__ == 'main':
    app.debug = True
    app.run('0.0.0.0')
