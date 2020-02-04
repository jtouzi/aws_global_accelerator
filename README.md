# AWS Global Accelerator Workshop
This repository contains instructions for getting started with AWS Global Accelerator.

In this workshop you will use the CloudFormtaion template to build a multiregion application, and then serve it with AWS Global Accelerator.

# Table of Content
* [What are we building](#design)
* Lab 0 - Launch the CloudFormation stack
* Lab 1 - Create an Accelerator
* Lab 2 - Intelligent traffic distribution
* Lab 3 - Fine-grained traffic control with Traffic Dials
  * EU-WEST-1 application upgrade or maintenance
  * Blue/Green deployment
* Lab 4 - Fine-grained traffic control with Endpoint Weights
* Lab 5 - Client Affinity
* Lab 7 - Continuous availability monitoring / Failover
* Bonus Labs - CloudWatch metrics and enabling flow logs

<a name="design"/>

## What are we building


## Lab 0 - Launch the CloudFormation stack

The CloudFormation stack below will create:
- a VPC with an Internet Gateway and two private subnets
- a Lambda function
- an Application Load Balancer with the Lambda function as target
- an IAM role the Lambda service will assume
- a permission to the Application Load Balancer to invoke the Lambda function

After the workshop, simply delete the CloudFormation stack to delete all the above resources.

Launch the CloudFormation stack in two or more AWS Regions of your choice, note down the different regions you choose.

The link will load the CloudFormation Dashboard and start the stack creation process in the chosen region.

| Region | Launch Template |
|------- | -------- |
| Oregon (us-west-2) | [![Launch stack in Oregon](https://s3.amazonaws.com/cloudformation-examples/cloudformation-launch-stack.png)](https://console.aws.amazon.com/cloudformation/home?region=us-west-2#/stacks/new?stackName=-AGAWorkshop&templateURL=https://jtouzi.s3.amazonaws.com/AGAWorkshop.template) | 
| Dublin (eu-west-1) | [![Launch stack in Dublin](https://s3.amazonaws.com/cloudformation-examples/cloudformation-launch-stack.png)](https://console.aws.amazon.com/cloudformation/home?region=eu-west-1#/stacks/new?stackName=AGAWorkshop&templateURL=https://jtouzi.s3.amazonaws.com/AGAWorkshop.template) | 
| Tokyo (ap-northeast-1) | [![Launch stack in Tokyo](https://s3.amazonaws.com/cloudformation-examples/cloudformation-launch-stack.png)](https://console.aws.amazon.com/cloudformation/home?region=ap-northeast-1#/stacks/new?stackName=-AGAWorkshop&templateURL=https://jtouzi.s3.amazonaws.com/AGAWorkshop.template) | 
| Sydney (ap-southeast-2) | [![Launch stack in Sydney](https://s3.amazonaws.com/cloudformation-examples/cloudformation-launch-stack.png)](https://console.aws.amazon.com/cloudformation/home?region=ap-southeast-2#/stacks/new?stackName=AGAWorkshop&templateURL=https://jtouzi.s3.amazonaws.com/AGAWorkshop.template) | 
| Canada (ca-central-1) | [![Launch stack in Canada](https://s3.amazonaws.com/cloudformation-examples/cloudformation-launch-stack.png)](https://console.aws.amazon.com/cloudformation/home?region=ca-central-1#/stacks/new?stackName=-AGAWorkshop&templateURL=https://jtouzi.s3.amazonaws.com/AGAWorkshop.template) | 
| Mumbai (ap-south-1) | [![Launch stack in Mumbai](https://s3.amazonaws.com/cloudformation-examples/cloudformation-launch-stack.png)](https://console.aws.amazon.com/cloudformation/home?region=ap-south-1#/stacks/new?stackName=AGAWorkshop&templateURL=https://jtouzi.s3.amazonaws.com/AGAWorkshop.template) | 

The template will automatically bring you to the CloudFormation Dashboard and start the stack creation process in the specified region. The default stack name "AGAWorkshop" (change it if you want to use a different name or if you want to deploy 2 endpoints in the same region), proceed through the wizard to launch the stack. Leave all options at their default values, but make sure to check the box to allow CloudFormation to create IAM roles on your behalf:

<kbd>![x](./img/cfn-create-template.png)</kbd>

<kbd>![x](./img/cfn-create.png)</kbd>

After you click on "Create stack", you will have the following window, it takes 3 to 4 minutes for the stack to be created.

<kbd>![x](./img/cfn-create-start.png)</kbd>

<kbd>![x](./img/cfn-create-complete.png)</kbd>

For this workshop we will use Oregon, Dublin and Tokyo regions, I've created two endpoints in Oregon region.

## Lab 1 - Create an Accelerator

- Open the Global Accelerator console at https://us-west-2.console.aws.amazon.com/ec2/v2/home?region=us-west-2#GlobalAcceleratorHome:
- Choose "Create accelerator" and  provide a name for your accelerator (AGAWorkshop)
- Choose "Next"

<kbd>![x](./img/accelerator-name.png)</kbd>

### Add the listeners (TCP port 80), choose "Next"

<kbd>![x](./img/add-listeners.png)</kbd>

### Add endpoint group (one per region in which you deployed the CloudFormation template), choose "Next"

<kbd>![x](./img/add-endpoint-groups.png)</kbd>

### Add endpoints to the endpoint groups (choose in the drop down the Application Load Balancers the template created), then choose "Create accelerator"

<kbd>![x](./img/add-endpoints.png)</kbd>

### The Accelerator will be "In progress" status, it takes about 5 minutes to move to "Deployed" status, you should be able to see the two static anycast IP addresses and the DNS assigned to the Accelerator

<kbd>![x](./img/accelerator-inprogress.png)</kbd>

### Once the accelerator is in "Deployed" status, select the accelerator and make sure all the endpoints are healthy

<kbd>![x](./img/accelerator-all-healthy.png)</kbd>

You should be able to access the application using the accelerator DNS.

<kbd>![x](./img/accelerator-browser.png)</kbd>

AWS Global Accelerator can access public and private EC2 instances and load balancers. Note that you can't access the Application Load Balancers the CloudFormation created directly using their DNS, as they are internal load balancers, AWS Global Accelerator will access them using private IP addresses. This is the AWS Global Accelerator **origin cloaking** feature, for more information see: https://docs.aws.amazon.com/global-accelerator/latest/dg/introduction-benefits-of-migrating.html

## Lab 2 - Intelligent traffic distribution

We kept the default traffic dials (100%)

<kbd>![x](./img/default-traffic-dials.png)</kbd>

Let's see how AWS Global Accelerator routes the requests based on the origin of the requester - we use the VPN to similuate requests from four different locations (Frankfurt, Herndon, Mumbai, Sao Paolo and Sydney) by running the following Bash command (use your accelerator DNS):

```
$ for i in {1..100}; do curl http://a05ba692c0635145f.awsglobalaccelerator.com/ --silent >> output.txt; done; cat output.txt | sort | uniq -c ; rm output.txt;
```

<kbd>![x](./img/100-frankfurt.png)</kbd>

<kbd>![x](./img/100-herndon.png)</kbd>

<kbd>![x](./img/100-mumbai.png)</kbd>

<kbd>![x](./img/100-saopaolo.png)</kbd>

<kbd>![x](./img/100-sydney.png)</kbd>

### Comments
1. Requests from Frankfurt and Mumbai are processed in EU-WEST-1 (Dublin)
2. Requests from Herndon and Sao-Paolo are processed in US-WEST-2 (Oregon), we have two endpoints in Oregon region, AWS Global Accelerator sends 50% of traffic to each endpoint (Endpoint weights).
3. Requests from Sydney are processed in AP-NORTHEAST-1 (Tokyo)

## Lab 3 - Fine-grained traffic control with Traffic Dials

### EU-WEST-1 application upgrade or maintenance

We would like to upgrade the application in EU-WEST-1 region, for this we would like to send the traffic to a different region, this is done by setting the Traffic Dial to 0 (zero) as shown below.

<kbd>![x](./img/0-eu-west-1-1.png)</kbd>
<kbd>![x](./img/0-eu-west-1-2.png)</kbd>

Let's see how AWS Global Accelerator handles traffic from Frankfurt and Mumbai, previously processed in EU-WEST-1 region.

<kbd>![x](./img/0-frankfurt.png)</kbd>

<kbd>![x](./img/0-mumbai.png)</kbd>

### Comments
Requests from Frankfurt are now processed in in US-WEST-1 (Oregon) and requests from Mumbai processed in AP-NORTHEAST-1 (Tokyo).

### The upgrade/maintenance is completed in EU-WEST-1. We want to test it by sending only 20% of the traffic it is supposed to handle.

<kbd>![x](./img/20-eu-west-1.png)</kbd>

Let see how AWS Global Accelerator handles traffic from Frankfurt and Mumbai, remember they were previously all processed in EU-WEST-1 (Dublin).

<kbd>![x](./img/20-frankfurt.png)</kbd>

<kbd>![x](./img/20-mumbai.png)</kbd>

### Comments
AWS Global Accelerator sends 20% of the traffic in EU-WEST-1 and 80% in the next closest available region, US-WEST-1 (Oregon) for requests from Frankfurt and AP-NORTHEAST-1 (Tokyo) for those from Mumbai.

Before you continue with the workshop, change back the traffic dial for US-WEST-1 region to 100%.

<kbd>![x](./img/default-traffic-dials.png)</kbd>

### Resources

Adjusting Traffic Flow With Traffic Dials: https://docs.aws.amazon.com/global-accelerator/latest/dg/about-endpoint-groups-traffic-dial.html

## Lab 4 - Fine-grained traffic control with Endpoint Weights
In US-WEST-2 (Oregon) region we have two endpoints, in Lab 3 the two endpoints processed the same amount of traffic, they have the default endpoint weight (128). Let's say the first endpoint has more capacipty than the second, and we want it to handle 80% of the traffic processed in the region, we can set endpoint weights to 200 and 50 respectively for the first and second endpoint. The first will handle 200 / (200 + 50) = 80%, the second 50 / (200 + 50) = 20%

<kbd>![x](./img/20-endpoint-weights.png)</kbd>

Let's see how AWS Global Accelerator will handle requests from Herndon.

<kbd>![x](./img/herndon-endpoint-weights.png)</kbd>

### Comments
The first endpoint in the endpoint group handles around 80% of the traffic. If you want Global Accelerator to stop sending traffic to an endpoint, you can change the weight for that resource to 0 as we did for traffic dials.

### Resources

Endpoint Weights: https://docs.aws.amazon.com/global-accelerator/latest/dg/about-endpoints-endpoint-weights.html

Change back the endpoint weights to the default (128).

## Lab 5 - Client Affinity
If we want AWS Global Accelerator to direct all requests from a user at a specific source (client) IP address to the same endpoint resource (to maintain client affinity), we can change the "Client Affinity" from "None" (default) to "Source IP" for the listener.

<kbd>![x](./img/client-affinity.png)</kbd>

Let's see how AWS Global Accelerator will handle requests from Herndon.

<kbd>![x](./img/herndon-client-affinity.png)</kbd>

### Comments

US-WEST-2 has two endpoints, but only one processed the 100 requests because they were all from the same IP address.

### Resources
Client Affinity: https://docs.aws.amazon.com/global-accelerator/latest/dg/about-listeners.html#about-listeners-client-affinity

## Lab 7 - Continuous availability monitoring / Failover

For some reason, our endpoint in AP-NORTHEAST-1 stops responding and the Application Load Balancer health check fails. AWS Global Accelerator will take up to 30 seconds (Health check interval) to notice the failure and to automatically redirect traffic to the next available region.

To simulate the failure, change the response status code returned by the [Lambda function](https://ap-northeast-1.console.aws.amazon.com/lambda/) from 200 to 403 for example and Save.

<kbd>![x](./img/lambda-function.png)</kbd>

After a maximum of 60 seconds (30 seconds for the ALB and 30 seconds for the Global Accelerator health chekcs), the endpoint status will become "Unhealthy" and Global Accelerator will start sending traffic automatically to the next available endpoint.

<kbd>![x](./img/failover.png)</kbd>

Let's see how AWS Global Accelerator will handle requests from Sydney, normally processed by AP-NORTHEAST-1 region.

<kbd>![x](./img/sydney-failover.png)</kbd>

### Comments
Sydney are now processed in US-WEST-2 region. AWS Global Accelerator will continue to monitor the endpoint, and will restart to send traffic to it once it becomes healthy.

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

version aws_account_id accelerator_id client_ip client_port gip gip_port endpoint_ip endpoint_port protocol ip_address_type packets bytes start_time end_time action log_status globalaccelerator_source_ip globalaccelerator_source_port endpoint_region globalaccelerator_region direction vpc_id
2.0 071855492661 ad530208-6600-4e87-9706-1f89a7e36abc 139.162.106.181 37760 75.2.76.16 80 172.31.21.192 80 TCP IPV4 0 0 1580605194 1580605204 ACCEPT OK - 0 ap-northeast-1 NRT57-2 INGRESS vpc-0f24e33eec64ec958
