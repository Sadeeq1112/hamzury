import qrcode
import os

certificates_dir = 'certificates'
url_prefix = 'https://hamzury.com/certificates/'

qr_codes_dir = 'qrcodes'
os.makedirs(qr_codes_dir, exist_ok=True)

for certificate in os.listdir(certificates_dir):
    if certificate.endswith('.jpg'):
        cert_url = url_prefix + certificate
        qr = qrcode.make(cert_url)  # Corrected this line
        qr.save(os.path.join(qr_codes_dir, f'{certificate}.png'))