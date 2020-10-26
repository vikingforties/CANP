import requests
import requests_html

f = open("siteURLlist.txt", "r")
headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0',
}
for site in f.readlines():
    status = requests.get(site, headers={'User-Agent': 'Custom'})
    print(".")
    if status.status_code > 350:
        print(str(status.status_code) + "   " + site)
    else:
        print(site + "    okay.")
f.close()
