#!/usr/bin/env python
import sys, pickle
from getopt import getopt
from random import shuffle


# define some global variables
quiet               = False
decrypt             = False
encrypt             = False
saveonly            = False
terminal            = True
passphrase          = ""
book_file           = ""
plainfile           = ""
index_file          = ""
cipher_file         = ""
output_file         = ""
index_output_file   = ""

def banner():
    return "StenCrypter (by LulululSecCF)"

def status(message):
    if not quiet: 
        print "\033[1;36m[*]\033[1;m %s" % message

def success(message):
    if not quiet: 
        print "\033[1;32m[*]\033[1;m %s" % message

def error(message):
    if not quiet:
        print
        print "\033[1;31m[x]\033[1;m %s" % message
        sys.exit(0)

def progress(message):
    if not quiet: 
        sys.stdout.write("\033[1;35m[+]\033[1;m %s" % message)
        sys.stdout.flush()


def usage():
    print banner()
    print
    print "Usage: " + sys.argv[0] + " [params...]"
    print "-h  --help                     - Prints this help menu."
    print "-p  --passphrase               - A message to be encrypted with the book."
    print "-P  --plainfile=text_file      - A file containing the plain text message to be encrypted."
    print "-b  --book=book_file           - Text file which will be indexed and used as a key. "
    print "-c  --cipher=cipher_file       - Already encrypted file."
    print "-q  --quiet                    - Does not print any status/info messages."
    print "-o  --outfile=output_file      - Save the message to a specified file."
    print "-d  --decrypt                  - Decrypt the input file and get the plaintext."
    print "-e  --encrypt                  - Encrypt the passphrase to a readable text."
    print "-i  --index=index_file         - The name of a previously saved index file."
    print "-s  --save=index_output_file   - It will save the book's index into a file."
    print "-S  --saveonly                 - Only saves the index format of a book without doing any encryption/decryption."
    print
    print "Examples: "
    print sys.argv[0] + " -b book3.txt -e -q -p \"We attack tomorrow!\" -o encrypt_email.txt "
    print sys.argv[0] + " -i book3.db -e -q -p \"They found me!?\" -o encrypt_email.txt "
    print sys.argv[0] + " -i book3.db -d -q -c encrypt_email.txt -o plain_email.txt"
    print sys.argv[0] + " -b book3.txt -S -s output_index.db -q"
    sys.exit(0)

def best_choice(t, t_bin):
    # Exit if there is no choice
    if len(t) < 2: 
        return (-1, 0)

    results   = None; 
    threshold = len(bin(len(t)-1)[2:]) - 1

    if len(t_bin) < threshold:
        t_bin += "0"*(threshold-len(t_bin))

    for i in range(len(t)):
        i_bin = bin(i)[2:].zfill(threshold)
        if i_bin == t_bin[:len(i_bin)]:
            results = (i, len(i_bin))

    return results

if len(sys.argv) < 2:
    usage()

try:
    ### read the commandline options
    opts, args = getopt(sys.argv[1:], "hp:b:c:P:qo:dei:s:S", ["help", "plainfile", "passphrase", "book", "cipher", "quiet", "outfile", "decrypt", "encrypt", "index", "save", "saveonly"])
except GetoptError as err:
    usage()

for o, a in opts:
    if   o in ("-h", "--help"):       usage()
    elif o in ("-p", "--passphrase"): passphrase  = a
    elif o in ("-P", "--plainfile"):  plainfile   = a
    elif o in ( "-b", "--book"):      book_file   = a
    elif o in ( "-c", "--cipher"):    cipher_file = a
    elif o in ( "-o", "--outfile"):   output_file = a
    elif o in ( "-i", "--index"):     index_file  = a
    elif o in ( "-q", "--quiet"):     quiet       = True 
    elif o in ( "-d", "--decrypt"):   decrypt     = True
    elif o in ( "-e", "--encrypt"):   encrypt     = True
    elif o in ( "-S", "--saveonly"):  saveonly    = True
    elif o in ( "-s", "--save"):      index_output_file = a
    else: assert False, "Unhandled Option"


### Initializing a index structure for later seed manipulation
index_db = dict()

if index_file:
    fp = open(index_file, 'r')  
    database = pickle.load(fp)
    fp.close()

    # index_db = {entry[0]:i for i, entry in enumerate(database)}

    success("Successfully loaded the index file.")
elif book_file:
    database = []; data = []
    ### Initialize the data
    for x in open(book_file , 'r'):
        if x.strip(): data += x.strip().split() 

    ### Link the list to the beginig
    data += data[:2]
    
    status("The file has %d entryies." % len(data))

    ### Index the data to a suitable database
    for ind in range(0, len(data) - 2, 1):
        key = tuple(data[ind:ind+2])
        val = data[ind+2]
        if not key in index_db: 
            index_db[key] = len(database)
            database.append([key, [val]])
        else:
            if not val in database[index_db[key]][1]:
                database[index_db[key]][1].append(val)

    success("Successfully indexed the book file.")
else:
    error("Please supply index or a book file.")

if index_output_file:
    status("Writing index structure to: %s" % index_output_file)
    fp = open(index_output_file, 'w')  
    pickle.dump(database, fp)
    fp.close()

    success("Successfully saved the index structure.")
    if saveonly: exit(0)

if plainfile:
    passphrase = open(plainfile, 'r').read().strip()

    success("Successfully read the plaintext file.")

if encrypt and passphrase: 
    pass_bin   =  "".join(['{0:07b}'.format(ord(x)) for x in passphrase])
    status("Passphrase   : %s" % passphrase)
    status("Binary Format: %s" % pass_bin)
    status("Binary Lenght: %d" % len(pass_bin))
elif not decrypt:
    usage()

### Find all indexes of tuples that start with a capital letter
capitals = [i for i, ind in enumerate(database) if ind[0][0][0].isupper()]

### Initializing a seed for the later shuffering. It is constructed
### by taking into account the whole index structure.
seed = sum([float(len(str(x[0]))) / len(str(x[1])) for x in database])
seed -= int(seed)

if encrypt:
    ### Find the largest suitable value that correlates with 
    ### the passphrase's bytestream
    start_id, ins_data = best_choice(capitals, pass_bin)
    progress("Encrypting   : %s" % pass_bin[:ins_data])

    ### Start building cipher text
    key, current = database[capitals[start_id]]
    cipher = list(key); 

    while ins_data < len(pass_bin):
        ins_size = 0
        start_id, ins_size = best_choice(current, pass_bin[ins_data:])
        if start_id != -1:
            cipher += [current[start_id]]
            # shuffle(database[index_db[key]][1], lambda: seed)
            # seed = start_id
        else:
            cipher += [current[0]]
        
        if ins_size > 0:
            if not quiet:
                sys.stdout.write("," + pass_bin[ins_data: ins_data + ins_size])
                sys.stdout.flush()
            ins_data += ins_size

        for ind in database:
            if tuple(cipher[-2:]) == ind[0]:
                key, current = ind
                break


    if not quiet: print
    success("Successfully encrypted the passphrase.")

    status("Cipher Text  : ")
    encrypt_result =  " ".join(cipher)
    print encrypt_result
elif decrypt and cipher_file:
    # Structoring the encrypted data for analysis
    encrypted = []
    for line in open(cipher_file, 'r'):
        encrypted += line.strip().split()

    success("Successfully parsed the cipher file.")

    output = ""
    for i, entry in enumerate(capitals):
        if database[entry][0] == tuple(encrypted[:2]):
            threshold = len(bin(len(capitals)-1)[2:]) - 1
            output    = bin(capitals.index(entry))[2:].zfill(threshold)
            break

    progress("Decripting   : %s" % output)

    for ind in range(0, len(encrypted) - 2 , 1):
        key = tuple(encrypted[ind:ind+2])
        val = encrypted[ind+2]
        for entry in database:
            if entry[0] == key:
                if len(entry[1]) > 1:
                    try:
                        threshold = len(bin(len(entry[1])-1)[2:]) - 1
                        temp = entry[1].index(val)
                        char = bin(temp)[2:].zfill(threshold)
                        output += char
                    except:
                        error("The book supplyed is not the one used for encryption.")
                    # shuffle(database[index_db[entry[0]]][1], lambda: seed)
                    # seed = temp
                    if not quiet:
                        sys.stdout.write(","+char)
                        sys.stdout.flush()
                break
    if not quiet: print

    status("Dec Byte len : %d" % len(output))

    success("Successfully decrypted the message.") 

    decrypt_result = ""
    status("Plain Text   : ")
    for asci in range(0, len(output), 7):
        word_bin = output[asci: asci+7]
        if len(word_bin) == 7:
            try:
                char = chr(int(word_bin, 2))
                sys.stdout.write(char)
                sys.stdout.flush()
                decrypt_result += char
            except:
                error("The book supplyed is not the one used for encryption.")

    decrypt_result += "\n"
    print
else:
    error("Please supply a cipher text file.")

if output_file:
    status("Saving the result to output file: %s" % output_file)
    fp = open(output_file, 'w')
    if   encrypt: fp.write(encrypt_result + "\n")
    elif decrypt: fp.write(decrypt_result + "\n")
    fp.close()
    success("Successfully saved the result.")
    



