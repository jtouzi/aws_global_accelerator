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

## Measuring Throughput
Throughput measurements help identify any congestion and/or packet loss experienced on a network and is a great measure of performance. If you want to test from a specific client, there are two ways which we suggest you use to measure the throughput:
1. Using a custom speed test tool that Global Accelerator has developed which can be found here: https://speedtest.globalaccelerator.aws/
2. Leveraging [iperf](https://iperf.fr/iperf-doc.php#3doc), let's use this tool to test throughput. If you don't have it installed on your laptop/Mac, you can [download it here](https://iperf.fr/en/iperf-download.php).
