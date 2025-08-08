import socket
from Crypto.Util.number import getPrime, inverse, bytes_to_long, long_to_bytes

def generate_keys():
    p = getPrime(512)
    q = getPrime(512)
    n = p * q
    phi = (p - 1) * (q - 1)
    e = 65537
    d = inverse(e, phi)
    return (n, e), (n, d)

# Generate Alice's keys
alice_public, alice_private = generate_keys()

# Start TCP server
server = socket.socket()
server.bind(('localhost', 12345))
server.listen(1)
print("[Alice] Waiting for Bob to connect...")
conn, addr = server.accept()
print(f"[Alice] Connected to Bob at {addr}")

# Send Alice's public key
conn.send(f"{alice_public[0]},{alice_public[1]}".encode())

# Receive Bob's public key
bob_key_data = conn.recv(4096).decode()
bob_n, bob_e = map(int, bob_key_data.split(','))
bob_public = (bob_n, bob_e)
print("[Alice] Public key from Bob received.")

while True:
    # Receive encrypted message from Bob
    cipher = conn.recv(4096).decode()
    if cipher == "exit":
        print("[Alice] Bob ended the chat.")
        break
    decrypted = pow(int(cipher), alice_private[1], alice_private[0])
    print(f"[Bob]: {long_to_bytes(decrypted).decode()}")

    # Send a reply
    message = input("[Alice] Enter message: ")
    if message.lower() == "exit":
        conn.send("exit".encode())
        break
    cipher_out = pow(bytes_to_long(message.encode()), bob_public[1], bob_public[0])
    conn.send(str(cipher_out).encode())

conn.close()
