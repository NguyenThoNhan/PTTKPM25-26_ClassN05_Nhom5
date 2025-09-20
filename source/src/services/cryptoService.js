// src/services/cryptoService.js
const crypto = require('crypto');
const algorithm = 'aes-256-cbc';
const secretKey = process.env.PRIVATE_KEY_SECRET;
const iv = crypto.randomBytes(16);

// Hàm mã hóa khóa bí mật trước khi lưu vào DB
exports.encrypt = (text) => {
    const cipher = crypto.createCipheriv(algorithm, Buffer.from(secretKey), iv);
    let encrypted = cipher.update(text);
    encrypted = Buffer.concat([encrypted, cipher.final()]);
    return { iv: iv.toString('hex'), encryptedData: encrypted.toString('hex') };
};

// Hàm giải mã khóa bí mật lấy từ DB
exports.decrypt = (hash) => {
    const decipher = crypto.createDecipheriv(algorithm, Buffer.from(secretKey), Buffer.from(hash.iv, 'hex'));
    let dec = decipher.update(Buffer.from(hash.encryptedData, 'hex'));
    dec = Buffer.concat([dec, decipher.final()]);
    return dec.toString();
};

// Hàm tạo cặp khóa RSA
exports.generateKeyPair = () => {
    return crypto.generateKeyPairSync('rsa', {
        modulusLength: 2048,
        publicKeyEncoding: { type: 'spki', format: 'pem' },
        privateKeyEncoding: { type: 'pkcs8', format: 'pem' }
    });
};

// Hàm tạo chữ ký số
exports.sign = (data, privateKey) => {
    const signer = crypto.createSign('sha256');
    signer.update(data);
    signer.end();
    return signer.sign(privateKey, 'hex');
};

// Hàm xác thực chữ ký (sẽ dùng ở giai đoạn sau)
exports.verify = (data, signature, publicKey) => {
    const verifier = crypto.createVerify('sha256');
    verifier.update(data);
    verifier.end();
    return verifier.verify(publicKey, signature, 'hex');
};