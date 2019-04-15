const Web3 = require("../../../node_modules/web3"); // import web3 v1.0 constructor

// use globally injected web3 to find the currentProvider and wrap with web3 v1.0
const getWeb3 = (provider) => {
    if(provider === undefined)  {
        provider = null;
    }
    const myWeb3 = new Web3(provider);
    return myWeb3;
};

function importKeystoreFile(keystore, password) {
    var keyObject = JSON.parse(keystore);
    console.log(keyObject, 'keyObject');
    var private_key = keythereum.recover(password, keyObject);
    console.log(private_key, 'private_key');
    const public_key = EthCrypto.publicKeyByPrivateKey(private_key.toString('hex'));
    console.log(public_key, 'public_key');
    /*try {
        var keyObject = JSON.parse(keystore);
        var private_key = keythereum.recover(password, keyObject);
        const public_key = EthCrypto.publicKeyByPrivateKey(private_key.toString('hex'));
        return {
            success: keyObject,
            public_key: public_key,
            address: JSON.parse(keystore).address
        }
    } catch (e) {
        return {
            error: true,
            message: 'Wrong secret password.'
        }
    }*/
}

function decryptKeystore(keystore, password) {
    try {
        return {
            success: keythereum.recover(password, JSON.parse(keystore)), to_string: keythereum.recover(password, JSON.parse(keystore)).toString('hex')
        }
    } catch (e) {
        return {
            error: true,
            message: 'Wrong secret password.'
        }
    }
}

async function decryptDataByPlainKey(encrypted_html, key) {
    try {
        const encrypted_obj = EthCrypto.cipher.parse(encrypted_html);
        const public_key = EthCrypto.publicKeyByPrivateKey(key);
        const address = EthCrypto.publicKey.toAddress(public_key);
        const html = await EthCrypto.decryptWithPrivateKey(key, encrypted_obj);

        return {
            success: {decrypted: html, address: address}
        }
    } catch (e) {
        return {
            error: true,
            message: 'Please enter correct private key.'
        }
    }
}

async function decryptDataByKeystore(encrypted_html, keystore, password) {
    try {
        var keyObject = JSON.parse(keystore);
        const encrypted_obj = EthCrypto.cipher.parse(encrypted_html);
        var private_key = keythereum.recover(password, keyObject).toString('hex');
        const html = await EthCrypto.decryptWithPrivateKey(private_key, encrypted_obj);

        return {
            success: {
                decrypted: html
            }
        }
    } catch (e) {
        return {
            error: true,
            message: 'Wrong password.'
        }
    }
}

function importPrivateKey(key) {
    try {
        //check if private_key is passed and if his length is not more than 64 hex characters
        const public_key = EthCrypto.publicKeyByPrivateKey(key);
        const address = EthCrypto.publicKey.toAddress(public_key);

        return {success: true, address: address, public_key: public_key};
    } catch (e) {
        return {
            error: true,
            message: 'Wrong data passed.'
        }
    }
}

module.exports = {getWeb3, importKeystoreFile, decryptKeystore, decryptDataByPlainKey, importPrivateKey, decryptDataByKeystore};