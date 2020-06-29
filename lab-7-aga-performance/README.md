# AWS Global Accelerator Performance

## Workshop Progress
✅ [Lab 0: Workshop Initialization](../lab-0-init)

✅ [Lab 1: Create your first AWS Global Accelerator](../lab-1-create-aws-global-accelerator)

✅ [Lab 2: Intelligent Traffic Distribution](../lab-2-traffic-distribution)

✅ [Lab 3: Fine-grained traffic control](../lab-3-fine-grained-control)

✅ [Lab 4: Client Affinity](../lab-4-client-affinity)

✅ [Lab 5: Continuous availability monitoring and Failover](../lab-5-observability)

✅ [Lab 6: Origin Cloaking](../lab-6-origin-cloaking)

**[Lab 7: AWS Global Accelerator Performance](../lab-7-aga-performance)**

- [AWS Global Accelerator speed comparison tool](#tool)
- [Measuring number of hops and detect loss](#traceroute)
- [Measuring RTT](#rtt)
- [Measuring the Total Time to download a 100KB file](#curl)

[Bonus Lab: CloudWatch metrics and enabling flow logs](../bonus-lab)

[Cleaning up](../clean-up)

## AWS Global Accelerator Performance

AWS Global Accelerator leverages the expansive, congestion-free, fully-managed AWS global network for majority of the network path between your users and AWS applications. It allows your user traffic to access the AWS global network from the edge location that is closest to your users. Regardless of where your users are located, Global Accelerator intelligently routes traffic to the best endpoint to provide consistent application performance and high availability for your users.

Global Accelerator's performance depends on many factors, such as the performance measurement tools used, proximity of users to AWS Regions, Global Accelerator’s high availability/fault isolating design, and proximity of users to Global Accelerator deployments. How to test performance:

- Best solution: do your own test from real client traffic with your own QoS system
- Second best solution: Use 3rd party real user measurement systems with thousands probes (e.g., Cedexis, Catchpoint, PerfOps…)
- Not ideal: use synthetic monitoring from data centers or other cloud providers with tens/hundreds of probes (e.g., ThousandEyes)
- Don't do this: test from EC2 to EC2, as that already uses the Amazon backbone

For this workshop we will use the first option, and test from our laptop/Mac (Windows, Linux, MacOS), I'll be using a Mac.

<details>
<summary>General guidance on performance measurement</summary>

It's recommended to capture 1000+ samples every hour for a day to avoid a single data-point from skewing result. Performance in the internet, for example, varies every hour as traffic goes into peak and faces congestion on the public internet. Hence, having samples per hour of the day gives a more complete picture on performance.

</details>

<a name="tool"/>

## AWS Global Accelerator speed comparison tool

You can use the AWS Global Accelerator Speed Comparison Tool to see Global Accelerator download speeds compared to direct internet downloads, across AWS Regions. This tool enables you to use your browser to see the performance difference when you transfer data using Global Accelerator. You choose a file size to download, and the tool downloads files over HTTPS/TCP from Application Load Balancers in different Regions to your browser. For each Region, you see a direct comparison of the download speeds.

To access the Speed Comparison Tool, copy the following URL into your browser: https://speedtest.globalaccelerator.aws

<details>
<summary>Important</summary>

Results may differ when you run the test multiple times. Download times can vary based on factors that are external to Global Accelerator, such as the quality, capacity, and distance of the connection in the last-mile network that you're using.

</details>

<a name="traceroute"/>

## Measuring number of hops and detect loss

Traceroute shows the route packets take from your computer to a host over an IP network. By default it uses ICMP protocol, some firewalls and routers often block the ICMP protocol completely or disallow the ICMP echo requests (ping requests). Let's use TCP Traceroute (send TCP packets) to see the number of hops and detect any packet loss, this will better reproduce the connection being made by our endpoints.

### TCP Traceroute to the Global Accelerator endpoint

```
$ sudo tcptraceroute aebd116200e8c28ad.awsglobalaccelerator.com
Selected device en0, address 192.168.1.73, port 50239 for outgoing packets
Tracing the path to aebd116200e8c28ad.awsglobalaccelerator.com (75.2.63.57) on TCP port 80 (http), 30 hops max
 1  192.168.1.254  4.037 ms  2.886 ms  3.544 ms
 2  * * *
 3  * * *
 4  12.242.112.22  25.039 ms  26.559 ms  25.094 ms
 5  12.244.76.10  28.199 ms  28.463 ms  27.643 ms
 6  * * *
 7  aebd116200e8c28ad.awsglobalaccelerator.com (75.2.63.57) [open]  25.227 ms  24.592 ms  27.608 ms
```

### TCP Traceroute to the AP-NORTHEAST-1 (Tokyo) ALB endpoint

```
$ sudo tcptraceroute AGAWo-Appli-1D492GIZTTFYA-981386931.ap-northeast-1.elb.amazonaws.com
Password:
Selected device en0, address 192.168.1.73, port 50959 for outgoing packets
Tracing the path to AGAWo-Appli-1D492GIZTTFYA-981386931.ap-northeast-1.elb.amazonaws.com (18.178.149.43) on TCP port 80 (http), 30 hops max
 1  192.168.1.254  4.714 ms  2.905 ms  2.988 ms
 2  * * *
 3  * * *
 4  12.242.112.2  31.884 ms  29.227 ms  31.798 ms
 5  192.205.36.206  26.885 ms  27.091 ms  26.146 ms
 6  if-ae-23-2.tcore2.ct8-chicago.as6453.net (64.86.79.120)  183.163 ms  183.957 ms  182.397 ms
 7  if-ae-51-2.tcore1.sqn-sanjose.as6453.net (64.86.79.15)  177.759 ms  176.816 ms  178.155 ms
 8  if-ae-18-2.tcore2.sv1-santaclara.as6453.net (63.243.205.73)  178.978 ms  178.285 ms  178.261 ms
 9  if-et-5-2.hcore1.kv8-chiba.as6453.net (209.58.86.143)  175.444 ms  175.304 ms  176.023 ms
10  if-ae-21-2.tcore1.tv2-tokyo.as6453.net (120.29.217.66)  177.717 ms  177.573 ms  177.637 ms
11  209.58.61.39  178.721 ms  179.414 ms  179.399 ms
12  * * *
13  * * *
14  * * *
15  * * *
16  * * *
17  52.95.31.53  181.518 ms  182.055 ms  184.540 ms
18  52.95.31.203  180.719 ms  181.248 ms  183.355 ms
19  52.95.31.194  181.552 ms  183.073 ms  181.166 ms
20  52.95.31.76  180.682 ms  178.953 ms  177.629 ms
21  * * *
22  * * *
23  * * *
24  * * *
25  * * *
26  * * *
27  * * *
28  ec2-18-178-149-43.ap-northeast-1.compute.amazonaws.com (18.178.149.43) [open]  182.720 ms  180.916 ms  182.119 ms
```

### TCP Traceroute to the EU-WEST-1 (Dublin) ALB endpoint

```
$ sudo tcptraceroute AGAWo-Appli-I6GT0VY1BMPM-2010336347.eu-west-1.elb.amazonaws.com
Selected device en0, address 192.168.1.73, port 51872 for outgoing packets
Tracing the path to AGAWo-Appli-I6GT0VY1BMPM-2010336347.eu-west-1.elb.amazonaws.com (34.254.18.175) on TCP port 80 (http), 30 hops max
 1  192.168.1.254  4.609 ms  3.449 ms  2.778 ms
 2  * * *
 3  * * *
 4  12.242.112.2  26.260 ms  30.632 ms  31.685 ms
 5  192.205.36.206  26.222 ms  26.032 ms  26.119 ms
 6  if-ae-37-3.tcore1.aeq-ashburn.as6453.net (66.198.154.68)  136.162 ms  134.912 ms  137.739 ms
 7  if-ae-30-2.tcore2.nto-newyork.as6453.net (63.243.216.20)  135.096 ms  134.792 ms  135.170 ms
 8  if-ae-32-2.tcore2.ldn-london.as6453.net (63.243.216.23)  141.569 ms  143.995 ms  142.700 ms
 9  80.231.20.82  153.059 ms  159.296 ms  153.060 ms
10  * * *
11  * * *
12  * * *
13  * * *
14  * * *
15  * * *
16  * * *
17  * * *
18  * * *
19  * * *
20  * * *
21  * * *
22  * * *
23  * * *
24  * * *
25  * * *
26  * * *
27  * * *
28  * * *
29  * * *
30  * * *
Destination not reached
```

### TCP Traceroute to a US-WEST-2 (Oregon) ALB endpoint

```
$ sudo tcptraceroute AGAWo-Appli-9CXFU1XOCSJ6-977194569.us-west-2.elb.amazonaws.com
Selected device en0, address 192.168.1.73, port 52890 for outgoing packets
Tracing the path to AGAWo-Appli-9CXFU1XOCSJ6-977194569.us-west-2.elb.amazonaws.com (52.10.138.194) on TCP port 80 (http), 30 hops max
 1  192.168.1.254  4.625 ms  2.927 ms  2.966 ms
 2  * * *
 3  * * *
 4  12.123.240.150  45.918 ms  52.357 ms  47.758 ms
 5  dlstx22crs.ip.att.net (12.122.1.210)  49.192 ms  47.598 ms  47.235 ms
 6  dvmco22crs.ip.att.net (12.122.2.86)  45.143 ms  239.280 ms  47.641 ms
 7  cr1.dvmco.ip.att.net (12.123.38.125)  43.648 ms  44.601 ms  45.121 ms
 8  12.91.154.234  45.973 ms  44.602 ms  45.205 ms
 9  * * *
10  * * *
11  * * *
12  * * *
13  * * *
14  * * *
15  * * *
16  * * *
17  * * *
18  * * *
19  * * *
20  * * *
21  * * *
22  * * *
23  * * *
24  * * *
25  * * *
26  * * *
27  * * *
28  * * *
29  * * *
30  * * *
Destination not reached
```

### Comments
It took 7 hops with Global Accelerator, 28 with the Tokyo ALB and 30+ for Dublin and Oregon ALBs.
<a name="rtt"/>
## Measuring RTT

The [Apache Bench (ab)](http://httpd.apache.org/docs/2.2/en/programs/ab.html) is a load testing and benchmarking tool for Hypertext Transfer Protocol (HTTP) server. From your laptop use Apache Bench tool to send 1000 measurements, 10 in parallel and have the tool provide first byte latency and last byte latency measure at different percentiles.
```
$ ab -n 1000 -c 10 http://GlobalAccelerator-OR-ALB-Endpoint/
```

Adjust -n to increase or decrease the number of requests to perform for the benchmarking session. The default is to just perform a single request which usually leads to non-representative benchmarking results.

Adjust -c to increase or decrease the number of multiple requests to perform at a time. Default is one request at a time. See http://httpd.apache.org/docs/2.2/en/programs/ab.html for information on options for the tool.

### With Global Accelerator endpoint

```
$ ab -n 1000 -c 10 http://aebd116200e8c28ad.awsglobalaccelerator.com/
...
Server Software:        awselb/2.0
Server Hostname:        aebd116200e8c28ad.awsglobalaccelerator.com
Server Port:            80

Document Path:          /
Document Length:        60 bytes

Concurrency Level:      10
Time taken for tests:   15.640 seconds
Complete requests:      1000
Failed requests:        495
   (Connect: 0, Receive: 0, Length: 495, Exceptions: 0)
Total transferred:      201495 bytes
HTML transferred:       60495 bytes
Requests per second:    63.94 [#/sec] (mean)
Time per request:       156.400 [ms] (mean)
Time per request:       15.640 [ms] (mean, across all concurrent requests)
Transfer rate:          12.58 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:       23   27   5.1     26     123
Processing:    98  127  11.0    125     264
Waiting:       98  127  11.0    125     264
Total:        144  154  11.0    152     290

Percentage of the requests served within a certain time (ms)
  50%    152
  66%    154
  75%    155
  80%    156
  90%    161
  95%    166
  98%    185
  99%    219
 100%    290 (longest request)
```

### With AP-NORTHEAST-1 (Tokyo) ALB endpoint

```
$ ab -n 1000 -c 10 http://AGAWo-Appli-1D492GIZTTFYA-981386931.ap-northeast-1.elb.amazonaws.com/
...
Server Software:        awselb/2.0
Server Hostname:        AGAWo-Appli-1D492GIZTTFYA-981386931.ap-northeast-1.elb.amazonaws.com
Server Port:            80

Document Path:          /
Document Length:        66 bytes

Concurrency Level:      10
Time taken for tests:   44.870 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      207000 bytes
HTML transferred:       66000 bytes
Requests per second:    22.29 [#/sec] (mean)
Time per request:       448.699 [ms] (mean)
Time per request:       44.870 [ms] (mean, across all concurrent requests)
Transfer rate:          4.51 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:      174  197  50.3    183     608
Processing:   188  242  90.0    207     759
Waiting:      188  242  90.0    207     758
Total:        365  439 101.8    393     948

Percentage of the requests served within a certain time (ms)
  50%    393
  66%    412
  75%    457
  80%    523
  90%    525
  95%    668
  98%    839
  99%    872
 100%    948 (longest request)
```

### With EU-WEST-1 (Dublin) ALB endpoint

```
$ ab -n 1000 -c 10 http://AGAWo-Appli-I6GT0VY1BMPM-2010336347.eu-west-1.elb.amazonaws.com/
...
Server Software:        awselb/2.0
Server Hostname:        AGAWo-Appli-I6GT0VY1BMPM-2010336347.eu-west-1.elb.amazonaws.com
Server Port:            80

Document Path:          /
Document Length:        61 bytes

Concurrency Level:      10
Time taken for tests:   40.655 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      202000 bytes
HTML transferred:       61000 bytes
Requests per second:    24.60 [#/sec] (mean)
Time per request:       406.554 [ms] (mean)
Time per request:       40.655 [ms] (mean, across all concurrent requests)
Transfer rate:          4.85 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:      134  195 109.3    150    1202
Processing:   150  208 104.1    167    1288
Waiting:      149  208 104.1    167    1288
Total:        286  403 147.0    323    1636

Percentage of the requests served within a certain time (ms)
  50%    323
  66%    394
  75%    503
  80%    522
  90%    597
  95%    693
  98%    741
  99%    781
 100%   1636 (longest request)
```

### With a US-WEST-2 (Oregon) ALB endpoint

```
$ ab -n 1000 -c 10 http://AGAWo-Appli-9CXFU1XOCSJ6-977194569.us-west-2.elb.amazonaws.com/
...
Server Software:        awselb/2.0
Server Hostname:        AGAWo-Appli-9CXFU1XOCSJ6-977194569.us-west-2.elb.amazonaws.com
Server Port:            80

Document Path:          /
Document Length:        60 bytes

Concurrency Level:      10
Time taken for tests:   20.784 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      201000 bytes
HTML transferred:       60000 bytes
Requests per second:    48.11 [#/sec] (mean)
Time per request:       207.843 [ms] (mean)
Time per request:       20.784 [ms] (mean, across all concurrent requests)
Transfer rate:          9.44 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:       81   92  39.3     85     402
Processing:    96  109  31.5    102     378
Waiting:       96  109  31.5    102     378
Total:        179  200  52.0    187     591

Percentage of the requests served within a certain time (ms)
  50%    187
  66%    190
  75%    192
  80%    194
  90%    206
  95%    253
  98%    437
  99%    458
 100%    591 (longest request)
```

### Comments

| Endpoint | RPS | TPR | TR (Kbytes/sec) | 50% | 75% | 90% | 99% | Longest request |
| -------: | -------: | -------: | -------: | -------: | -------: | -------: |-------: | -------: |
| Global Accelerator | 63.94 | 156ms | 12.58 | 152ms | 155ms | 161ms | 219ms | 290ms |
| Tokyo ALB | 22.29 | 448ms | 4.51 | 393ms | 457ms | 525ms | 872ms | 948ms |
| Dublin ALB | 24.6 | 406ms | 4.85 | 323ms | 503ms | 597ms | 781ms | 1636ms |
| Oregon ALB | 48.11 | 207ms | 9.44 | 187ms | 192ms | 206ms | 458ms | 591ms |

- RPS: Request per second (mean)
- TPR: Time per request (mean)
- TR: Transfer rate 

<a name="curl"/>

## Measuring the Total Time to download a 100KB file from AWS Global Accelerator vs directly from the ALBs

**[cURL](https://curl.haxx.se/)** is an excellent tool for debugging web requests, it allows to find the response time of a request.

Create a file named *"curl-format.txt"* with the following content:

```
  Name Lookup:  %{time_namelookup}s |
  Time to Connect:  %{time_connect}s |
  Time To Transfer:  %{time_pretransfer}s |
  Time To First Byte:  %{time_starttransfer}s |
  Total Time:  %{time_total}s\n
```

Run the following command, use your ALB and Global Accelerator endpoints, don't forget to add */100KB* to the endpoints - we are sending 20 requests per endpoint, with a pause of 3 seconds. **Again, it's recommended to capture 1000+ samples every hour for a day to avoid a single data-point from skewing result.**

```
for i in {1..20}; do curl -w @curl-format.txt -o /dev/null -s GlobalAccelerator-OR-ALB-Endpoint/100KB; sleep 3; done | grep -v time_total:0
```

### With Global Accelerator endpoint

```
$ for i in {1..20}; do curl -w @curl-format.txt -o /dev/null -s aebd116200e8c28ad.awsglobalaccelerator.com/100KB; sleep 3; done | grep -v time_total:0
Name Lookup:  0.004302s |  Time to Connect:  0.029995s |  Time To Transfer:  0.030072s |  Time To First Byte:  0.207302s |  Total Time:  0.413625s
Name Lookup:  0.004453s |  Time to Connect:  0.030549s |  Time To Transfer:  0.030621s |  Time To First Byte:  0.205575s |  Total Time:  0.440627s
Name Lookup:  0.005101s |  Time to Connect:  0.030617s |  Time To Transfer:  0.030693s |  Time To First Byte:  0.316582s |  Total Time:  0.471398s
Name Lookup:  0.004477s |  Time to Connect:  0.029641s |  Time To Transfer:  0.029715s |  Time To First Byte:  0.205560s |  Total Time:  0.420285s
Name Lookup:  0.004586s |  Time to Connect:  0.031909s |  Time To Transfer:  0.031983s |  Time To First Byte:  0.210130s |  Total Time:  0.416375s
Name Lookup:  0.004822s |  Time to Connect:  0.031789s |  Time To Transfer:  0.031895s |  Time To First Byte:  0.197049s |  Total Time:  0.397660s
Name Lookup:  0.005022s |  Time to Connect:  0.032124s |  Time To Transfer:  0.032205s |  Time To First Byte:  0.199342s |  Total Time:  0.429480s
Name Lookup:  0.004605s |  Time to Connect:  0.031744s |  Time To Transfer:  0.031817s |  Time To First Byte:  0.209927s |  Total Time:  0.417744s
Name Lookup:  0.012840s |  Time to Connect:  0.039522s |  Time To Transfer:  0.039610s |  Time To First Byte:  0.206226s |  Total Time:  0.685462s
Name Lookup:  0.013742s |  Time to Connect:  0.039449s |  Time To Transfer:  0.039528s |  Time To First Byte:  0.215496s |  Total Time:  0.420609s
Name Lookup:  0.004495s |  Time to Connect:  0.030949s |  Time To Transfer:  0.030998s |  Time To First Byte:  0.208667s |  Total Time:  0.420049s
Name Lookup:  0.004711s |  Time to Connect:  0.031158s |  Time To Transfer:  0.031224s |  Time To First Byte:  0.238568s |  Total Time:  0.453413s
Name Lookup:  0.012709s |  Time to Connect:  0.040213s |  Time To Transfer:  0.040280s |  Time To First Byte:  0.245723s |  Total Time:  0.452203s
Name Lookup:  0.004240s |  Time to Connect:  0.029495s |  Time To Transfer:  0.029573s |  Time To First Byte:  0.209385s |  Total Time:  0.412565s
Name Lookup:  0.004242s |  Time to Connect:  0.031328s |  Time To Transfer:  0.031392s |  Time To First Byte:  0.214517s |  Total Time:  0.444886s
Name Lookup:  0.004827s |  Time to Connect:  0.030832s |  Time To Transfer:  0.030888s |  Time To First Byte:  0.236171s |  Total Time:  0.468354s
Name Lookup:  0.005101s |  Time to Connect:  0.030605s |  Time To Transfer:  0.030673s |  Time To First Byte:  0.219511s |  Total Time:  0.437052s
Name Lookup:  0.005019s |  Time to Connect:  0.031147s |  Time To Transfer:  0.031220s |  Time To First Byte:  0.218360s |  Total Time:  0.426351s
Name Lookup:  0.004631s |  Time to Connect:  0.031153s |  Time To Transfer:  0.031225s |  Time To First Byte:  0.205672s |  Total Time:  0.414355s
Name Lookup:  0.004408s |  Time to Connect:  0.036504s |  Time To Transfer:  0.036591s |  Time To First Byte:  0.224801s |  Total Time:  0.439200s
```

### With AP-NORTHEAST-1 (Tokyo) ALB endpoint

```
$ for i in {1..20}; do curl -w @curl-format.txt -o /dev/null -s AGAWo-Appli-1D492GIZTTFYA-981386931.ap-northeast-1.elb.amazonaws.com/100KB; sleep 3; done | grep -v time_total:0
Name Lookup:  0.064513s |  Time to Connect:  0.244220s |  Time To Transfer:  0.244275s |  Time To First Byte:  1.015984s |  Total Time:  1.930865s
Name Lookup:  0.004974s |  Time to Connect:  0.186893s |  Time To Transfer:  0.186951s |  Time To First Byte:  0.497774s |  Total Time:  1.429233s
Name Lookup:  0.005107s |  Time to Connect:  0.183582s |  Time To Transfer:  0.183637s |  Time To First Byte:  0.421589s |  Total Time:  1.328985s
Name Lookup:  0.004664s |  Time to Connect:  0.181916s |  Time To Transfer:  0.181998s |  Time To First Byte:  0.444044s |  Total Time:  1.355639s
Name Lookup:  0.005107s |  Time to Connect:  0.183854s |  Time To Transfer:  0.183928s |  Time To First Byte:  0.460933s |  Total Time:  1.552164s
Name Lookup:  0.049718s |  Time to Connect:  0.231609s |  Time To Transfer:  0.231673s |  Time To First Byte:  0.532838s |  Total Time:  1.459811s
Name Lookup:  0.004173s |  Time to Connect:  0.186730s |  Time To Transfer:  0.186843s |  Time To First Byte:  0.468618s |  Total Time:  1.604985s
Name Lookup:  0.004967s |  Time to Connect:  0.186270s |  Time To Transfer:  0.186349s |  Time To First Byte:  0.495409s |  Total Time:  1.424522s
Name Lookup:  0.004380s |  Time to Connect:  0.186509s |  Time To Transfer:  0.186575s |  Time To First Byte:  0.439746s |  Total Time:  1.647907s
Name Lookup:  0.005097s |  Time to Connect:  0.186731s |  Time To Transfer:  0.186804s |  Time To First Byte:  0.459450s |  Total Time:  1.565335s
Name Lookup:  0.004191s |  Time to Connect:  0.184598s |  Time To Transfer:  0.184660s |  Time To First Byte:  0.476427s |  Total Time:  1.571533s
Name Lookup:  0.012301s |  Time to Connect:  0.193061s |  Time To Transfer:  0.193126s |  Time To First Byte:  0.479551s |  Total Time:  1.585273s
Name Lookup:  0.004574s |  Time to Connect:  0.219360s |  Time To Transfer:  0.219430s |  Time To First Byte:  0.522377s |  Total Time:  1.643978s
Name Lookup:  0.005103s |  Time to Connect:  0.186390s |  Time To Transfer:  0.186459s |  Time To First Byte:  0.459718s |  Total Time:  1.554603s
Name Lookup:  0.004346s |  Time to Connect:  0.185755s |  Time To Transfer:  0.185830s |  Time To First Byte:  0.477583s |  Total Time:  1.587635s
Name Lookup:  0.005125s |  Time to Connect:  0.182057s |  Time To Transfer:  0.182129s |  Time To First Byte:  0.448973s |  Total Time:  1.546703s
Name Lookup:  0.063127s |  Time to Connect:  0.242885s |  Time To Transfer:  0.242966s |  Time To First Byte:  0.544495s |  Total Time:  1.852474s
Name Lookup:  0.063066s |  Time to Connect:  0.244853s |  Time To Transfer:  0.244942s |  Time To First Byte:  0.512554s |  Total Time:  1.720995s
Name Lookup:  0.004473s |  Time to Connect:  0.186015s |  Time To Transfer:  0.186063s |  Time To First Byte:  0.438102s |  Total Time:  1.371680s
Name Lookup:  0.005004s |  Time to Connect:  0.183820s |  Time To Transfer:  0.183892s |  Time To First Byte:  0.458083s |  Total Time:  3.247779s
```

### With EU-WEST-1 (Dublin) ALB endpoint

```
$ for i in {1..20}; do curl -w @curl-format.txt -o /dev/null -s AGAWo-Appli-I6GT0VY1BMPM-2010336347.eu-west-1.elb.amazonaws.com/100KB; sleep 3; done | grep -v time_total:0
Name Lookup:  0.061972s |  Time to Connect:  0.200771s |  Time To Transfer:  0.200850s |  Time To First Byte:  0.582081s |  Total Time:  1.467863s
Name Lookup:  0.004538s |  Time to Connect:  0.170738s |  Time To Transfer:  0.170797s |  Time To First Byte:  0.413691s |  Total Time:  1.277743s
Name Lookup:  0.004164s |  Time to Connect:  0.150290s |  Time To Transfer:  0.150332s |  Time To First Byte:  0.366134s |  Total Time:  1.114313s
Name Lookup:  0.005114s |  Time to Connect:  0.146203s |  Time To Transfer:  0.146266s |  Time To First Byte:  0.361701s |  Total Time:  1.370703s
Name Lookup:  0.004159s |  Time to Connect:  0.154432s |  Time To Transfer:  0.154539s |  Time To First Byte:  0.525248s |  Total Time:  1.581253s
Name Lookup:  0.004283s |  Time to Connect:  0.160705s |  Time To Transfer:  0.160761s |  Time To First Byte:  0.396577s |  Total Time:  1.843917s
Name Lookup:  0.004625s |  Time to Connect:  0.154302s |  Time To Transfer:  0.154362s |  Time To First Byte:  0.368382s |  Total Time:  1.572708s
Name Lookup:  0.004169s |  Time to Connect:  0.143916s |  Time To Transfer:  0.143994s |  Time To First Byte:  0.364600s |  Total Time:  1.484849s
Name Lookup:  0.004979s |  Time to Connect:  0.144825s |  Time To Transfer:  0.144903s |  Time To First Byte:  0.363151s |  Total Time:  1.486377s
Name Lookup:  0.005109s |  Time to Connect:  0.162082s |  Time To Transfer:  0.162131s |  Time To First Byte:  0.422452s |  Total Time:  1.680661s
Name Lookup:  0.005118s |  Time to Connect:  0.142291s |  Time To Transfer:  0.142373s |  Time To First Byte:  0.354762s |  Total Time:  1.459401s
Name Lookup:  0.005091s |  Time to Connect:  0.150546s |  Time To Transfer:  0.150628s |  Time To First Byte:  0.368316s |  Total Time:  1.536406s
Name Lookup:  0.004195s |  Time to Connect:  0.146436s |  Time To Transfer:  0.146492s |  Time To First Byte:  0.362194s |  Total Time:  1.500276s
Name Lookup:  0.013378s |  Time to Connect:  0.160399s |  Time To Transfer:  0.160463s |  Time To First Byte:  0.387554s |  Total Time:  1.568047s
Name Lookup:  0.004482s |  Time to Connect:  0.153597s |  Time To Transfer:  0.153678s |  Time To First Byte:  0.381814s |  Total Time:  1.579033s
Name Lookup:  0.013374s |  Time to Connect:  0.150218s |  Time To Transfer:  0.150292s |  Time To First Byte:  0.355720s |  Total Time:  1.449300s
Name Lookup:  0.004167s |  Time to Connect:  0.155485s |  Time To Transfer:  0.155544s |  Time To First Byte:  0.367638s |  Total Time:  1.549134s
Name Lookup:  0.004186s |  Time to Connect:  0.163817s |  Time To Transfer:  0.163894s |  Time To First Byte:  0.394241s |  Total Time:  1.678243s
Name Lookup:  0.064445s |  Time to Connect:  0.202200s |  Time To Transfer:  0.202277s |  Time To First Byte:  0.417065s |  Total Time:  1.521327s
Name Lookup:  0.004476s |  Time to Connect:  0.145908s |  Time To Transfer:  0.145989s |  Time To First Byte:  0.345045s |  Total Time:  1.484631s
```

### With a US-WEST-2 (Oregon) ALB endpoint

```
$ for i in {1..20}; do curl -w @curl-format.txt -o /dev/null -s AGAWo-Appli-9CXFU1XOCSJ6-977194569.us-west-2.elb.amazonaws.com/100KB; sleep 3; done | grep -v time_total:0
Name Lookup:  0.066599s |  Time to Connect:  0.149003s |  Time To Transfer:  0.149079s |  Time To First Byte:  0.323751s |  Total Time:  0.993618s
Name Lookup:  0.004476s |  Time to Connect:  0.090832s |  Time To Transfer:  0.090890s |  Time To First Byte:  0.234477s |  Total Time:  0.754488s
Name Lookup:  0.005205s |  Time to Connect:  0.089855s |  Time To Transfer:  0.089926s |  Time To First Byte:  0.252486s |  Total Time:  0.937387s
Name Lookup:  0.005105s |  Time to Connect:  0.090557s |  Time To Transfer:  0.090611s |  Time To First Byte:  0.264607s |  Total Time:  0.957222s
Name Lookup:  0.005101s |  Time to Connect:  0.088653s |  Time To Transfer:  0.088737s |  Time To First Byte:  0.241471s |  Total Time:  0.925332s
Name Lookup:  0.004179s |  Time to Connect:  0.088384s |  Time To Transfer:  0.088438s |  Time To First Byte:  0.255688s |  Total Time:  0.932052s
Name Lookup:  0.005109s |  Time to Connect:  0.090012s |  Time To Transfer:  0.090100s |  Time To First Byte:  0.265141s |  Total Time:  0.989958s
Name Lookup:  0.005116s |  Time to Connect:  0.089384s |  Time To Transfer:  0.089454s |  Time To First Byte:  0.250176s |  Total Time:  0.933267s
Name Lookup:  0.074336s |  Time to Connect:  0.165917s |  Time To Transfer:  0.165980s |  Time To First Byte:  0.341575s |  Total Time:  2.524230s
Name Lookup:  0.004985s |  Time to Connect:  0.086565s |  Time To Transfer:  0.086632s |  Time To First Byte:  0.256454s |  Total Time:  1.830530s
Name Lookup:  0.004777s |  Time to Connect:  0.089732s |  Time To Transfer:  0.089804s |  Time To First Byte:  0.244834s |  Total Time:  1.677145s
Name Lookup:  0.012448s |  Time to Connect:  0.098443s |  Time To Transfer:  0.098523s |  Time To First Byte:  0.269891s |  Total Time:  1.630636s
Name Lookup:  0.004484s |  Time to Connect:  0.087777s |  Time To Transfer:  0.087851s |  Time To First Byte:  0.257454s |  Total Time:  2.322361s
Name Lookup:  0.004173s |  Time to Connect:  0.086836s |  Time To Transfer:  0.086910s |  Time To First Byte:  0.252875s |  Total Time:  1.819901s
Name Lookup:  0.005107s |  Time to Connect:  0.090533s |  Time To Transfer:  0.090627s |  Time To First Byte:  0.238066s |  Total Time:  1.687419s
Name Lookup:  0.004949s |  Time to Connect:  0.089977s |  Time To Transfer:  0.090050s |  Time To First Byte:  0.227284s |  Total Time:  1.591376s
Name Lookup:  0.013827s |  Time to Connect:  0.100097s |  Time To Transfer:  0.100170s |  Time To First Byte:  0.259987s |  Total Time:  1.630748s
Name Lookup:  0.004387s |  Time to Connect:  0.087829s |  Time To Transfer:  0.087872s |  Time To First Byte:  0.245601s |  Total Time:  1.590556s
Name Lookup:  0.067417s |  Time to Connect:  0.155489s |  Time To Transfer:  0.155552s |  Time To First Byte:  0.316172s |  Total Time:  0.840259s
Name Lookup:  0.004208s |  Time to Connect:  0.088712s |  Time To Transfer:  0.088801s |  Time To First Byte:  0.245562s |  Total Time:  0.761126s
```
### Comments
With AWS Global Accelerator endpoint, cURL was in average 70%, 67% and 61% faster than respectively Tokyo, Dublin and Oregon ALB endpoints (from my location).
