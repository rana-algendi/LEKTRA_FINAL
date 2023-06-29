from flask import *
from predict_py import predict  # From predict.py import function named predict

app = Flask(__name__)


@app.route('/true/', methods=['POST'])
def true():
    if request.method == 'POST':
        f = request.files['file']
    return predict(f)


if __name__ == '__main__':
    app.debug = True
    app.run('0.0.0.0')
