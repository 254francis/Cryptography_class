import socket
from Crypto.Util.number import getPrime, inverse, bytes_to_long, long_to_bytes

# Generate 1024-bit RSA keys
p = getPrime(512)
q = getPrime(512)
n = p * q
phi = (p - 1) * (q - 1)
e = 65537
d = inverse(e, phi)

# Start TCP server
server = socket.socket()
server.bind(('localhost', 12345))
server.listen(1)
print("[Alice] Waiting for Bob to connect...")

conn, addr = server.accept()
print(f"[Alice] Connected to Bob at {addr}")

# Send public key to Bob
conn.send(f"{n},{e}".encode())

# Receive ciphertext
cipher = int(conn.recv(4096).decode())
print(f"[Alice] Received ciphertext: {cipher}")

# Decrypt message
decrypted = pow(cipher, d, n)
message = long_to_bytes(decrypted).decode()

print(f"[Alice] Decrypted message from Bob: {message}")
conn.close()
