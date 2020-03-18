# Mythical Mysfits: Multi-Region Control with AWS Global Accelerator

![mysfits-welcome](/images/mysfits-welcome.png)

## Lab 6 - Continuous availability monitoring / Failover

For some reason, our endpoint in AP-NORTHEAST-1 stops responding and the Application Load Balancer health check fails. AWS Global Accelerator will take up to 30 seconds (Health check interval) to notice the failure and to automatically redirect traffic to the next available region.

To simulate the failure, change the response status code returned by the [Lambda function](https://ap-northeast-1.console.aws.amazon.com/lambda/) from 200 to 403 for example and Save.

<kbd>![x](./img/lambda-function.png)</kbd>

After a maximum of 60 seconds (30 seconds for the ALB and 30 seconds for the Global Accelerator health chekcs), the endpoint status will become "Unhealthy" and Global Accelerator will start sending traffic automatically to the next available endpoint.

<kbd>![x](./img/failover.png)</kbd>

Let's see how AWS Global Accelerator will handle requests from Sydney, normally processed by AP-NORTHEAST-1 region.

<kbd>![x](./img/sydney-failover.png)</kbd>

### Comments
Sydney are now processed in US-WEST-2 region. AWS Global Accelerator will continue to monitor the endpoint, and will restart to send traffic to it once it becomes healthy.

<a name="lab7"/>

## Bonus Labs - CloudWatch metrics and enabling flow logs

### CloudWatch metrics

AWS Global Accelerator currently publishes [three metrics](https://docs.aws.amazon.com/global-accelerator/latest/dg/cloudwatch-monitoring.html#cloudwatch-metrics-global-accelerator) (AWS/GlobalAccelerator namespace) to Amazon CloudWatch:
- NewFlowCount: The total number of new TCP and UDP flows (or connections) established from clients to endpoints in the time period.
- ProcessedBytesIn: The total number of incoming bytes processed by the accelerator, including TCP/IP headers.
- ProcessedBytesOut: The total number of outgoing bytes processed by the accelerator, including TCP/IP headers.

To view the metrics for your accelerator, open CloudWatch in US-WEST-2 region: https://us-west-2.console.aws.amazon.com/cloudwatch/home?region=us-west-2#metricsV2:graph=~();query=~'*7bAWS*2fGlobalAccelerator*2cAccelerator*7d

### Enabling flow logs

Flow logs enable you to capture information about the IP address traffic going to and from network interfaces in your accelerator in AWS Global Accelerator. Flow log data is published to Amazon S3, where you can retrieve and view your data after you've created a flow log. For more information and steps to enable flow logs, see https://docs.aws.amazon.com/global-accelerator/latest/dg/monitoring-global-accelerator.flow-logs.html.

**Sample log file**

```
version aws_account_id accelerator_id client_ip client_port gip gip_port endpoint_ip endpoint_port protocol ip_address_type packets bytes start_time end_time action log_status globalaccelerator_source_ip globalaccelerator_source_port endpoint_region globalaccelerator_region direction vpc_id
2.0 071855492661 ad530208-6600-4e87-9706-1f89a7e36abc 139.162.106.181 37760 75.2.76.16 80 172.31.21.192 80 TCP IPV4 0 0 1580605194 1580605204 ACCEPT OK - 0 ap-northeast-1 NRT57-2 INGRESS vpc-0f24e33eec64ec958
```

<a name="clean"/>

## Cleaning up
