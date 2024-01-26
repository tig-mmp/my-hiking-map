# Project Documentation

## Install dDependencies

```bash
composer install
```

## JWT Key Generation

To enhance the security of JWT keys, an RSA private key with a passphrase can be generated using OpenSSL. Follow the steps below:

### Generate RSA Private Key

Use the following command to generate an RSA private key:

```bash
openssl genpkey -algorithm RSA -aes256 -pass pass:YourPassphrase -out  -out ./config/jwt/private.pem
```

Replace YourPassphrase with your desired passphrase.

### Extract Public Key from Private Key

After generating the private key, extract the corresponding public key:

```bash
openssl rsa -pubout -passin pass:YourPassphrase -in ./config/jwt/private.pem -out ./config/jwt/public.pem
```

### Configure passPhrase in .env file

Configure the passphrase in the .env file. Open the file and add the following line:

```bash
JWT_PASS_PHRASE="YourPassphrase"
```
