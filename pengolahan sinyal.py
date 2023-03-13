from scipy.signal import butter, lfilter
import scipy.io.wavfile as wavfile
import numpy as np
import json
import pyrebase
import argparse

my_parser = argparse.ArgumentParser()
my_parser.add_argument('--input',  required=True)


args = my_parser.parse_args()
file_input = args.input
ts = file_input.split('_')[1]
ts = ts.split('.')[0]
ts = int(ts)*1000

id_device = ##3
lowcut = ##27200.0
highcut = ##33800.0
threshold_dB_medium = ##39.76
threshold_medium = ##5
threshold_dB_high = ##68
selisih_high = ##4

counter = ##0
tmp_dB = ##0
medium = False
arc_counter = 0
average_dB = 0
tmp_average_dB = 0
high = False
counter_low = 0
counter_medium = 0
counter_high = 0
counter_iter = 0
average_dB_one_minute = [0, 0]
arc_counter_one_minute = [0, 0]

data = []

#config = {
 #   "apiKey": "AIzaSyDnkz_iUnkw51DusI2PPaNQ1SACEhuPkBA",
 #   "authDomain": "padisel-8330f.firebaseapp.com",
 #   "databaseURL": "https://padisel-8330f.firebaseio.com",
 #   "storageBucket": "padisel-8330f.appspot.com"
#}


def butter_bandpass(lowcut, highcut, fs, order=5):
    nyq = 0.5 * fs #Implementasi NYQuist
    low = lowcut / nyq 
    high = highcut / nyq
    b, a = butter(order, [low, high], btype='band')
    return b, a


def butter_bandpass_filter(data, lowcut, highcut, fs, order=5):
    b, a = butter_bandpass(lowcut, highcut, fs, order=order)
    y = lfilter(b, a, data)
    return y


def Average(lst):
    return sum(lst) / len(lst)


def getdB(signal):
    chunk_signal = []
    for chunk in range(0, num_chunk):
        chunk_signal.append(
            np.mean(signal[chunk*chunk_size:(chunk+1)*chunk_size]**2))

    logsn = 10*np.log10(chunk_signal)
    return logsn


def sendData(Database, id_device, data):
    firebase = pyrebase.initialize_app(config)
    db = firebase.database()
    db.child(Database).child(id_device)
    db.set(data)
    print('send')


if __name__ == "__main__":

    try:
        with open('data.json') as json_file:
            data_json = json.load(json_file)
            medium = data_json['medium']
            arc_counter = data_json['arc_counter']
            average_dB = data_json['average_dB']
            tmp_average_dB = data_json['tmp_average_dB']
            high = data_json['high']
            counter_low = data_json['counter_low']
            counter_medium = data_json['counter_medium']
            counter_high = data_json['counter_high']
            average_dB_one_minute[0] = data_json['average_dB_one_minute'][1]
            arc_counter_one_minute[0] = data_json['arc_counter_one_minute'][1]

    except:
        print('open error')

    #SST-IT Parametric Retrieval
    fs_rate, signal = wavfile.read(file_input)

    l_audio = len(signal.shape)
    if l_audio == 2:
        signal = signal.sum(axis=1) / 2
    N = signal.shape[0]

    # Sample rate and desired cutoff frequencies (in Hz).
    fs = fs_rate

    # Filter a noisy signal SST_IT Iterative.
    y = butter_bandpass_filter(signal, lowcut, highcut, fs, order=6)

    chunk_size = int(fs/##100)
    num_chunk = len(y) // chunk_size
    filterd_signal = []
    for chunk in range(0, num_chunk):
        filterd_signal.append(
            np.mean(y[chunk*chunk_size:(chunk+1)*chunk_size]**2))

    logsn = 10*np.log10(filterd_signal)
    average_dB_one_minute[1] = Average(logsn)
    dB_max = max(logsn)
    dB_min = min(logsn)
    iter_data = iter(logsn)

    try:
        while True:
            if not medium:
                one_second = [next(iter_data) for _ in range(100)]
                counter_iter += 1
                average_dB = Average(one_second)
                # parameter medium
                if(average_dB > tmp_average_dB and average_dB > threshold_dB_medium):
                    counter += 1
                else:
                    counter = 0
                tmp_average_dB = average_dB

            # counter medium
            if (not high and average_dB > threshold_dB_medium and counter > threshold_medium):
                print(average_dB, 'medium')
                counter_medium += 1
                medium = True
                if counter_medium == 1:
                    tmp_data_notification = {
                        'first_time_pd': ts + (counter_iter*1000)
                    }
                    sendData("Notification/PD", id_device,
                             tmp_data_notification)
            if (average_dB <= threshold_dB_medium):
                medium = False

            # Parameter high
            if (medium):
                one_second = [next(iter_data) for _ in range(100)]
                counter_iter += 1
                average_dB = Average(one_second)
                for i in range(len(one_second)-1):
                    if(one_second[i+1] < threshold_dB_high):
                        continue
                    selisih = one_second[i+1]-one_second[i]
                    if selisih >= selisih_high:
                        arc_counter += 1
                        if arc_counter == 1:
                            tmp_data_notification = {"first_time_arc": ts +
                                                     (counter_iter*1000)}
                            sendData("Notification/Arc", id_device,
                                     tmp_data_notification)
                        print(
                            'arc',  one_second[i], one_second[i+1], counter_iter-1, i+1)

            if (arc_counter > ##20):
                high = True
            else:
                high = False

            if high:
                print(average_dB, 'high')
                counter_high += 1
            if (not high and not medium):
                print(average_dB, 'low')
                counter_low += 1

            if(high):
                state = 'high'
            elif(medium and not high):
                state = 'medium'
            else:
                state = 'low'

            firebase = pyrebase.initialize_app(config)
            timestamp = ts+(counter_iter*1000)
            db = firebase.database()
            db.child("PD").child(id_device)
            tmp_data = {"dB_min": dB_min,
                        "dB_max": dB_max,
                        "average_dB_one_minute": average_dB_one_minute,
                        "arc_counter_one_minute": arc_counter_one_minute,
                        "dBA": average_dB,
                        "state": state,
                        "id_device": id_device,
                        "timestamp": timestamp,
                        "arc_counter": arc_counter,
                        }
            db.push(tmp_data)
            arc_counter_one_minute[1] = arc_counter
            x = {
                "medium": medium,
                "arc_counter": arc_counter,
                "average_dB": average_dB,
                "tmp_average_dB": tmp_average_dB,
                "average_dB_one_minute": average_dB_one_minute,
                "arc_counter_one_minute": arc_counter_one_minute,
                "high": high,
                "counter_low": counter_low,
                "counter_high": counter_high,
                "counter_medium": counter_medium
            }

    # convert into JSON:
            y = json.dumps(x)

    except StopIteration as err:
        print(err)
    with open('data.json', 'w') as f:
        json.dump(x, f)
