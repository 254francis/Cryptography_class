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

# Generate Bob's keys
bob_public, bob_private = generate_keys()

# Connect to Alice
client = socket.socket()
client.connect(('localhost', 12345))

# Receive Alice's public key
alice_key_data = client.recv(4096).decode()
alice_n, alice_e = map(int, alice_key_data.split(','))
alice_public = (alice_n, alice_e)

# Send Bob's public key to Alice
client.send(f"{bob_public[0]},{bob_public[1]}".encode())
print("[Bob] Public key exchange complete.")

while True:
    # Send a message to Alice
    message = input("[Bob] Enter message: ")
    if message.lower() == "exit":
        client.send("exit".encode())
        break
    cipher_out = pow(bytes_to_long(message.encode()), alice_public[1], alice_public[0])
    client.send(str(cipher_out).encode())

    # Receive Alice's reply
    cipher_in = client.recv(4096).decode()
    if cipher_in == "exit":
        print("[Bob] Alice ended the chat.")
        break
    decrypted = pow(int(cipher_in), bob_private[1], bob_private[0])
    print(f"[Alice]: {long_to_bytes(decrypted).decode()}")

client.close()
