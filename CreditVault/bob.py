import socket
from Crypto.Util.number import bytes_to_long

# Connect to Alice
client = socket.socket()
client.connect(('localhost', 12345))

# Receive public key
data = client.recv(4096).decode()
n, e = map(int, data.split(','))

# Input message
message = input("[Bob] Enter a message to send to Alice: ")
m = bytes_to_long(message.encode())

# Encrypt and send
cipher = pow(m, e, n)
client.send(str(cipher).encode())
print("[Bob] Ciphertext sent!")

client.close()
