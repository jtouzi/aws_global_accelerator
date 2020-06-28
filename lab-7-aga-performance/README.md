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

[Bonus Lab: CloudWatch metrics and enabling flow logs](../bonus-lab)

[Cleaning up](../clean-up)

AWS Global Accelerator leverages the expansive, congestion-free, fully-managed AWS global network for majority of the network path between your users and AWS applications. It allows your user traffic to access the AWS global network from the edge location that is closest to your users. Regardless of where your users are located, Global Accelerator intelligently routes traffic to the best endpoint to provide consistent application performance and high availability for your users.

Global Accelerator's performance depends on many factors, such as the performance measurement tools used, proximity of users to AWS Regions, Global Accelerator’s high availability/fault isolating design, and proximity of users to Global Accelerator deployments. How to test performance:

- Best solution: do your own test from real client traffic with your own QoS system
- Second best solution: Use 3rd party real user measurement systems with thousands probes (e.g., Cedexis, Catchpoint, PerfOps…)
- Not ideal: use synthetic monitoring from data centers or other cloud providers with tens/hundreds of probes (e.g., ThousandEyes)
- Don't do this: test from EC2 to EC2, as that already uses the Amazon backbone

For this test, use your laptop/Mac (Windows, Linux, MacOS), I'll be using a Mac.

<details>
<summary>General guidance on performance measurement</summary>

It's recommended to capture 1000+ samples every hour for a day to avoid a single data-point from skewing result. Performance in the internet, for example, varies every hour as traffic goes into peak and faces congestion on the public internet. Hence, having samples per hour of the day gives a more complete picture on performance.

</details>

## Time to download a 100KB file directly from the ALBs in different regions and from AWS Global Accelerator
Create a file named *"curl-format.txt"* with the following content:

```
        Name Lookup:  %{time_namelookup}s
    Time to Connect:  %{time_connect}s
   Time To Transfer:  %{time_pretransfer}s
 Time To First Byte:  %{time_starttransfer}s
         Total Time:  %{time_total}s\n
```

Run the following command, use your ALB and Global Accelerator endpoints, don't forget to add */100KB* to the endpoints - the command will take 50 * 2 = 100 seconds to complete.

```
for i in {1..50}; do curl -w @curl-format.txt -o /dev/null -s GlobalAccelerator-OR-ALB-Endpoint/100KB; sleep 2; done | grep -v time_total:0
```

### With Global Accelerator endpoint

```
$ for i in {1..50}; do curl -w @curl-format.txt -o /dev/null -s aebd116200e8c28ad.awsglobalaccelerator.com/100KB; sleep 2; done | grep -v time_total:0
        Name Lookup:  0.264439s    Time to Connect:  0.291237s   Time To Transfer:  0.291318s Time To First Byte:  0.451677s         Total Time:  0.680415s
        Name Lookup:  0.004427s    Time to Connect:  0.033053s   Time To Transfer:  0.033138s Time To First Byte:  0.210932s         Total Time:  0.448396s
        Name Lookup:  0.064962s    Time to Connect:  0.090545s   Time To Transfer:  0.090616s Time To First Byte:  0.278465s         Total Time:  0.483150s
        Name Lookup:  0.005096s    Time to Connect:  0.030859s   Time To Transfer:  0.030918s Time To First Byte:  0.212045s         Total Time:  0.423764s
        Name Lookup:  0.004773s    Time to Connect:  0.030949s   Time To Transfer:  0.030999s Time To First Byte:  0.221981s         Total Time:  0.448046s
        Name Lookup:  0.004873s    Time to Connect:  0.032195s   Time To Transfer:  0.032272s Time To First Byte:  0.212964s         Total Time:  0.443366s
        Name Lookup:  0.004313s    Time to Connect:  0.033170s   Time To Transfer:  0.033220s Time To First Byte:  0.209426s         Total Time:  0.416624s
        Name Lookup:  0.005112s    Time to Connect:  0.030819s   Time To Transfer:  0.030874s Time To First Byte:  0.206569s         Total Time:  0.412126s
        Name Lookup:  0.004389s    Time to Connect:  0.033951s   Time To Transfer:  0.034027s Time To First Byte:  0.249200s         Total Time:  0.521165s
        Name Lookup:  0.004472s    Time to Connect:  0.031309s   Time To Transfer:  0.031367s Time To First Byte:  0.233600s         Total Time:  0.440814s
        Name Lookup:  0.005108s    Time to Connect:  0.031403s   Time To Transfer:  0.031466s Time To First Byte:  0.241857s         Total Time:  0.474102s
        Name Lookup:  0.005181s    Time to Connect:  0.030900s   Time To Transfer:  0.030982s Time To First Byte:  0.229148s         Total Time:  0.437232s
        Name Lookup:  0.004608s    Time to Connect:  0.030839s   Time To Transfer:  0.030917s Time To First Byte:  0.226787s         Total Time:  0.457976s
        Name Lookup:  0.004727s    Time to Connect:  0.030877s   Time To Transfer:  0.030957s Time To First Byte:  0.209547s         Total Time:  0.415506s
        Name Lookup:  0.004789s    Time to Connect:  0.031060s   Time To Transfer:  0.031138s Time To First Byte:  0.197912s         Total Time:  0.428157s
        Name Lookup:  0.004739s    Time to Connect:  0.033187s   Time To Transfer:  0.033258s Time To First Byte:  0.205820s         Total Time:  0.434860s
        Name Lookup:  0.005036s    Time to Connect:  0.031489s   Time To Transfer:  0.031544s Time To First Byte:  0.210797s         Total Time:  0.416962s
        Name Lookup:  0.004219s    Time to Connect:  0.031313s   Time To Transfer:  0.031390s Time To First Byte:  0.205873s         Total Time:  0.425557s
        Name Lookup:  0.005097s    Time to Connect:  0.032509s   Time To Transfer:  0.032573s Time To First Byte:  0.270132s         Total Time:  0.622762s
        Name Lookup:  0.004113s    Time to Connect:  0.031980s   Time To Transfer:  0.032062s Time To First Byte:  0.215086s         Total Time:  0.443937s
        Name Lookup:  0.004652s    Time to Connect:  0.029905s   Time To Transfer:  0.029968s Time To First Byte:  0.210371s         Total Time:  0.453563s
        Name Lookup:  0.004548s    Time to Connect:  0.029708s   Time To Transfer:  0.029782s Time To First Byte:  0.375447s         Total Time:  0.579173s
        Name Lookup:  0.004203s    Time to Connect:  0.029997s   Time To Transfer:  0.030051s Time To First Byte:  0.213364s         Total Time:  0.427322s
        Name Lookup:  0.004397s    Time to Connect:  0.030693s   Time To Transfer:  0.030999s Time To First Byte:  0.217331s         Total Time:  0.431095s
        Name Lookup:  0.005048s    Time to Connect:  0.031507s   Time To Transfer:  0.031577s Time To First Byte:  0.205366s         Total Time:  0.412901s
        Name Lookup:  0.004613s    Time to Connect:  0.030468s   Time To Transfer:  0.030517s Time To First Byte:  0.193929s         Total Time:  0.398671s
        Name Lookup:  0.012405s    Time to Connect:  0.038005s   Time To Transfer:  0.038080s Time To First Byte:  0.222345s         Total Time:  0.455140s
        Name Lookup:  0.004479s    Time to Connect:  0.032150s   Time To Transfer:  0.032238s Time To First Byte:  0.205034s         Total Time:  0.410418s
        Name Lookup:  0.004157s    Time to Connect:  0.030859s   Time To Transfer:  0.030911s Time To First Byte:  0.238250s         Total Time:  0.458268s
        Name Lookup:  0.004530s    Time to Connect:  0.030309s   Time To Transfer:  0.030384s Time To First Byte:  0.225054s         Total Time:  0.468660s
        Name Lookup:  0.004895s    Time to Connect:  0.036216s   Time To Transfer:  0.036279s Time To First Byte:  0.207062s         Total Time:  0.414798s
        Name Lookup:  0.013196s    Time to Connect:  0.040668s   Time To Transfer:  0.040761s Time To First Byte:  0.232047s         Total Time:  0.475179s
        Name Lookup:  0.005083s    Time to Connect:  0.030829s   Time To Transfer:  0.030903s Time To First Byte:  0.231192s         Total Time:  0.440231s
        Name Lookup:  0.005314s    Time to Connect:  0.031249s   Time To Transfer:  0.031306s Time To First Byte:  0.213955s         Total Time:  0.435275s
        Name Lookup:  0.004967s    Time to Connect:  0.031806s   Time To Transfer:  0.031876s Time To First Byte:  0.209980s         Total Time:  0.655534s
        Name Lookup:  0.014128s    Time to Connect:  0.040540s   Time To Transfer:  0.040598s Time To First Byte:  0.217508s         Total Time:  0.450927s
        Name Lookup:  0.004147s    Time to Connect:  0.029468s   Time To Transfer:  0.029535s Time To First Byte:  0.232301s         Total Time:  0.440380s
        Name Lookup:  0.005097s    Time to Connect:  0.031414s   Time To Transfer:  0.031484s Time To First Byte:  0.226993s         Total Time:  0.436409s
        Name Lookup:  0.004552s    Time to Connect:  0.030686s   Time To Transfer:  0.030753s Time To First Byte:  0.210477s         Total Time:  0.417745s
        Name Lookup:  0.004244s    Time to Connect:  0.033483s   Time To Transfer:  0.033545s Time To First Byte:  0.200728s         Total Time:  0.408410s
        Name Lookup:  0.004500s    Time to Connect:  0.032464s   Time To Transfer:  0.032536s Time To First Byte:  0.205050s         Total Time:  0.407028s
        Name Lookup:  0.004867s    Time to Connect:  0.030811s   Time To Transfer:  0.030880s Time To First Byte:  0.210775s         Total Time:  0.425450s
        Name Lookup:  0.004451s    Time to Connect:  0.030567s   Time To Transfer:  0.030643s Time To First Byte:  0.208636s         Total Time:  0.440089s
        Name Lookup:  0.005113s    Time to Connect:  0.032544s   Time To Transfer:  0.032614s Time To First Byte:  0.203889s         Total Time:  0.488436s
        Name Lookup:  0.004962s    Time to Connect:  0.030029s   Time To Transfer:  0.030076s Time To First Byte:  0.227998s         Total Time:  0.434124s
        Name Lookup:  0.004331s    Time to Connect:  0.030706s   Time To Transfer:  0.030775s Time To First Byte:  0.196180s         Total Time:  0.402175s
        Name Lookup:  0.004449s    Time to Connect:  0.031841s   Time To Transfer:  0.031895s Time To First Byte:  0.209146s         Total Time:  0.443584s
        Name Lookup:  0.004667s    Time to Connect:  0.030175s   Time To Transfer:  0.030253s Time To First Byte:  0.206492s         Total Time:  0.421674s
        Name Lookup:  0.004935s    Time to Connect:  0.031922s   Time To Transfer:  0.032000s Time To First Byte:  0.204626s         Total Time:  0.410237s
        Name Lookup:  0.004191s    Time to Connect:  0.031536s   Time To Transfer:  0.031595s Time To First Byte:  0.204390s         Total Time:  0.425455s
```

## Measuring Throughput
Throughput measurements help identify any congestion and/or packet loss experienced on a network and is a great measure of performance. If you want to test from a specific client, there are two ways which we suggest you use to measure the throughput:
1. Using a custom speed test tool that Global Accelerator has developed which can be found here: https://speedtest.globalaccelerator.aws/
2. Leveraging [iperf](https://iperf.fr/iperf-doc.php#3doc), let's use this tool to test throughput. If you don't have it installed on your laptop/Mac, you can [download it here](https://iperf.fr/en/iperf-download.php).
