import qrcode
import os

# Directory containing certificate images
certificates_dir = 'certificates'
# URL prefix for the certificates
url_prefix = 'https://hamzury.com/certificates/'

# Create a directory for QR codes
qr_codes_dir = 'qrcodes'
os.makedirs(qr_codes_dir, exist_ok=True)

# Generate QR codes for each certificate
for certificate in os.listdir(certificates_dir):
    if certificate.endswith('.jpg'):
        cert_url = url_prefix + certificate
        qr = qrcode.make(cert_url)  # Corrected this line
        qr.save(os.path.join(qr_codes_dir, f'{certificate}.png'))