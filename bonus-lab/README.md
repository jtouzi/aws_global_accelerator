# Multi-Region traffic management with AWS Global Accelerator - Bonus lab

## Workshop Progress
✅ [Lab 0: Workshop Initialization](../lab-0-init)

✅ [Lab 1: Create your first AWS Global Accelerator](../lab-1-create-aws-global-accelerator)

✅ [Lab 2: Implement Intelligent Traffic Distribution](../lab-2-traffic-distribution)

✅ [Lab 3: Implement Fine-grained traffic control](../lab-3-fine-grained-control)

✅ [Lab 4: Implement Client Affinity](../lab-4-client-affinity)

✅ [Lab 5: Continuous availability monitoring and Failover](../lab-5-observability)

**[Bonus Lab: CloudWatch metrics and enabling flow logs](../bonus-lab)**

[Cleaning up](../clean-up)

## CloudWatch metrics
AWS Global Accelerator currently publishes three metrics (AWS/GlobalAccelerator namespace) to Amazon CloudWatch:

* **NewFlowCount:** The total number of new TCP and UDP flows (or connections) established from clients to endpoints in the time period.
* **ProcessedBytesIn:** The total number of incoming bytes processed by the accelerator, including TCP/IP headers.
* **ProcessedBytesOut:** The total number of outgoing bytes processed by the accelerator, including TCP/IP headers.
To view the metrics for your accelerator, open CloudWatch in US-WEST-2 region: https://us-west-2.console.aws.amazon.com/cloudwatch/home?region=us-west-2#metricsV2:graph=~();query=~'*7bAWS*2fGlobalAccelerator*2cAccelerator*7d

## Enable flow logs

Flow logs enable you to capture information about the IP address traffic going to and from network interfaces in your accelerator in AWS Global Accelerator. Flow log data is published to Amazon S3, where you can retrieve and view your data after you've created a flow log. For more information and steps to enable flow logs, see https://docs.aws.amazon.com/global-accelerator/latest/dg/monitoring-global-accelerator.flow-logs.html.

Sample log file
```
version aws_account_id accelerator_id client_ip client_port gip gip_port endpoint_ip endpoint_port protocol ip_address_type packets bytes start_time end_time action log_status globalaccelerator_source_ip globalaccelerator_source_port endpoint_region globalaccelerator_region direction vpc_id
2.0 071855492661 ad530208-6600-4e87-9706-1f89a7e36abc 139.162.106.181 37760 75.2.76.16 80 172.31.21.192 80 TCP IPV4 0 0 1580605194 1580605204 ACCEPT OK - 0 ap-northeast-1 NRT57-2 INGRESS vpc-0f24e33eec64ec958
```
## Analyze and visualize flow logs using Amazon Athena and Amazon QuickSight

AWS Solutions Architecture team has published a blog post that uses [Amazon Athena](http://aws.amazon.com/athena) (an interactive query tool for S3-based data) and [Amazon QuickSight](https://aws.amazon.com/quicksight) (a cloud-based business intelligence service) to analyze and visualize the flow log data and develop actionable business value. With this solution you can troubleshoot reachability issues for your application, identify security vulnerabilities, or get an overview of how end-users access your application. For more information and details about it implementation see: https://aws.amazon.com/blogs/networking-and-content-delivery/analyzing-and-visualizing-aws-global-accelerator-flow-logs-using-amazon-athena-and-amazon-quicksight/
