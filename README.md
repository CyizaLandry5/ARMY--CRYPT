# ARMY  CRYPT
 📄 Project Overview:

This project is a secure military communication platform built on a single dynamic web page. 
Soldiers can encrypt classified mission reports before storing them in a database, ensuring that sensitive information is never exposed in plaintext. 
Users can choose between sending encrypted data or retrieving and decrypting existing reports, all handled smoothly within one interface using interactive buttons and forms. 
Access to the real messages is only possible by supplying the correct private decryption key.

🛠️ Technologies and Techniques:

The system uses PHP for backend processing, MariaDB for data storage, and RSA public-key cryptography (OpenSSL) to encrypt and decrypt messages. 
Frontend interactions are managed with HTML, CSS for styling, and lightweight JavaScript to dynamically switch between the send and retrieve forms without reloading the page. All encryption happens securely on the server side before database insertion, following best practices for data confidentiality and integrity.
