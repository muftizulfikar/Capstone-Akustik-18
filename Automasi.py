cd /home/hg401/Documents/Workspace/Github/DSP
ts=$(date +%s)

current_time=$(date "+%Y.%m.%d-%H.%M.%S")
filename=$current_time\_$ts.wav
arecord -f S16_LE -r 256000 -d 60  --device="hw:2,0" $filename

python3 test3.py --input $filename

import socket
UDP_IP = "127.0.0.1"
UDP_PORT = 5005
MESSAGE = {"dB_min": dB_min,
                        "dB_max": dB_max,
                        "average_dB_one_minute": average_dB_one_minute,
                        "arc_counter_one_minute": arc_counter_one_minute,
                        "dBA": average_dB,
                        "state": state,
                        "id_device": id_device,
                        "timestamp": timestamp,
                        "arc_counter": arc_counter,
                        }

sock = socket.socket(socket.AF_INET, # Internet
                     socket.SOCK_DGRAM) # UDP
sock.sendto(MESSAGE, (UDP_IP, UDP_PORT))

uint16_t get_block(uint8_t *buffer) {
  uint16_t buffer_index= 0;
  while (true) {
    int c = getchar_timeout_us(100);
    if (c != PICO_ERROR_TIMEOUT && buffer_index < BUFFER_LENGTH) {
      buffer[buffer_index++] = (c & 0xFF);
    } else {
      break;
    }
  }
  return buffer_index;
}

def send_data_block(uart, bytes, counter):
    length = len(bytes) - counter
    if length > 255: length = 255
    out = bytearray(length + 6)
    out[0] = 0x55                   # Head
    out[1] = 0x3C                   # Sync
    out[2] = 0x01                   # Block Type
    out[3] = length                 # Data length
    # Set the data
    for i in range(0, length):
        out[i + 4] = bytes[counter + i]
    # Compute checksum
    cs = 0
    for i in range (2, length + 6 - 2):
        cs += out[i]
    cs &= 0xFF
    out[length + 6 - 2] = cs        # Checksum
    out[length + 6 - 1] = 0x55      # Trailer
    counter += length
    r = uart.write(out)
    return counter
	
	def await_ack(uart, timeout=2000):
    buffer = bytes()
    now = (time_ns() // 1000000)
    while ((time_ns() // 1000000) - now) < timeout:
        if uart.in_waiting > 0:
            buffer += uart.read(uart.in_waiting)
            if "\n" in buffer.decode():
                show_verbose("RX: " + buffer[:-1].decode())
                return True
    # Error -- No Ack received
    return False
