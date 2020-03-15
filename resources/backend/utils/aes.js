import CryptoJS from 'crypto-js'; //引用AES源码js

const SECRET_KEY = 'OUM6iqqNsAmSDsx97YXz6IGbCaTs8Lvw6nD4zqMtn0Y';
const VI = 'Dsx96IG8LvzqMt0Y';

// AES加密
export function encrypt(decrypted) {
    if(typeof decrypted !== 'string' && typeof decrypted !== 'number'){
        return '';
    }

    let secret_key = CryptoJS.MD5(SECRET_KEY).toString();
    let vi = VI;

    decrypted = CryptoJS.enc.Utf8.parse(decrypted);
    secret_key = CryptoJS.enc.Utf8.parse(secret_key);
    vi = CryptoJS.enc.Utf8.parse(vi);

    let encrypted = CryptoJS.AES.encrypt(decrypted, secret_key, {
        iv: vi,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    }).toString();

    return encrypted;
}

// AES解密
export function decrypt(encrypted) {
    if(typeof encrypted !== 'string' && typeof encrypted !== 'number'){
        return '';
    }

    let secret_key = CryptoJS.MD5(SECRET_KEY).toString();
    let vi = VI;

    secret_key = CryptoJS.enc.Utf8.parse(secret_key);
    vi = CryptoJS.enc.Utf8.parse(vi);

    let decrypted = CryptoJS.AES.decrypt(encrypted, secret_key, {
        iv: vi,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    }).toString(CryptoJS.enc.Utf8);

    return decrypted;
}
