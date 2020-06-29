# Multi-Region traffic management with AWS Global Accelerator - Bonus lab

## Workshop Progress
✅ [Lab 0: Workshop Initialization](../lab-0-init)

✅ [Lab 1: Create your first AWS Global Accelerator](../lab-1-create-aws-global-accelerator)

✅ [Lab 2: Implement Intelligent Traffic Distribution](../lab-2-traffic-distribution)

✅ [Lab 3: Implement Fine-grained traffic control](../lab-3-fine-grained-control)

✅ [Lab 4: Implement Client Affinity](../lab-4-client-affinity)

✅ [Lab 5: Continuous availability monitoring and Failover](../lab-5-observability)

✅ [Lab 6: Implement Origin Cloaking](../lab-6-origin-cloaking)

✅ [Lab 7: AWS Global Accelerator Performance](../lab-7-aga-performance)

**[Bonus Lab: CloudWatch metrics and enabling flow logs](../bonus-lab)**

[Cleaning up](../clean-up)

Monitoring is an important part of maintaining the availability and performance of Global Accelerator and your AWS solutions. You should collect monitoring data from all of the parts of your AWS solution so that you can more easily debug a multi-point failure if one occurs. AWS provides several tools for monitoring your Global Accelerator resources and activity, and responding to potential incidents.

## CloudWatch metrics
AWS Global Accelerator publishes data points to Amazon CloudWatch for your accelerators. CloudWatch enables you to retrieve statistics about those data points as an ordered set of time-series data, known as metrics. For more information see: https://docs.aws.amazon.com/global-accelerator/latest/dg/cloudwatch-monitoring.html.

AWS Global Accelerator currently publishes three metrics (AWS/GlobalAccelerator namespace) to Amazon CloudWatch:

* **NewFlowCount:** The total number of new TCP and UDP flows (or connections) established from clients to endpoints in the time period.
* **ProcessedBytesIn:** The total number of incoming bytes processed by the accelerator, including TCP/IP headers.
* **ProcessedBytesOut:** The total number of outgoing bytes processed by the accelerator, including TCP/IP headers.
To view the metrics for your accelerator, open CloudWatch in US-WEST-2 region: https://us-west-2.console.aws.amazon.com/cloudwatch/home?region=us-west-2#metricsV2:graph=~();query=~'*7bAWS*2fGlobalAccelerator*2cAccelerator*7d

## Enable flow logs

Flow logs enable you to capture information about the IP address traffic going to and from network interfaces in your accelerator in AWS Global Accelerator. Flow log data is published to Amazon S3, where you can retrieve and view your data after you've created a flow log. Currently you can't enable flow logs using the web console, you must AWS Global Accelerator [UpdateAcceleratorAttributes API](https://docs.aws.amazon.com/global-accelerator/latest/api/API_UpdateAcceleratorAttributes.html), for more information and steps to enable flow logs, see https://docs.aws.amazon.com/global-accelerator/latest/dg/monitoring-global-accelerator.flow-logs.html.

Sample log file:
```
version aws_account_id accelerator_id client_ip client_port gip gip_port endpoint_ip endpoint_port protocol ip_address_type packets bytes start_time end_time action log_status globalaccelerator_source_ip globalaccelerator_source_port endpoint_region globalaccelerator_region direction vpc_id
2.0 071855492661 ad530208-6600-4e87-9706-1f89a7e36abc 139.162.106.181 37760 75.2.76.16 80 172.31.21.192 80 TCP IPV4 0 0 1580605194 1580605204 ACCEPT OK - 0 ap-northeast-1 NRT57-2 INGRESS vpc-0f24e33eec64ec958
```

## Analyze and visualize flow logs using Amazon Athena and Amazon QuickSight

AWS Solutions Architecture team has published a blog post that uses [Amazon Athena](http://aws.amazon.com/athena) (an interactive query tool for S3-based data) and [Amazon QuickSight](https://aws.amazon.com/quicksight) (a cloud-based business intelligence service) to analyze and visualize the flow log data and develop actionable business value. With this solution you can troubleshoot reachability issues for your application, identify security vulnerabilities, or get an overview of how end-users access your application.

For more information and implementation details see: https://aws.amazon.com/blogs/networking-and-content-delivery/analyzing-and-visualizing-aws-global-accelerator-flow-logs-using-amazon-athena-and-amazon-quicksight/

# Checkpoint

We have completed all the labs, let [DELELE the resources](../clean-up)!
