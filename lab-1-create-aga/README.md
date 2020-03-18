# Mythical Mysfits: Multi-Region Control with AWS Global Accelerator

![mysfits-welcome](/images/mysfits-welcome.png)

## Workshop Progress
✅ [Lab 0: Workshop Initialization](../lab-0-init)

**[Lab 1: Create your first AWS Global Accelerator](../lab-1-create-aga)**

[Lab 2: Implement Intelligent Traffic Distribution](../lab-2-traffic-distribution)

[Lab 3: Implement Fine-grained traffic control](../lab-3-fine-grained-control)

[Lab 4: Implement Client Affinity](../lab-4-client-affinity)

[Lab 5: Implement Observability](../lab-5-observability)

[Bonus Lab: CloudWatch metrics and enabling flow logs](../lab-bonus)

[Workshop Cleanup - TBD](tbd-cleanup)

## Lab 1 - Create your first AWS Global Accelerator

Traffic management is one of the largest pieces of the puzzle when it comes to multi-region architectures. The one that we will focus on today is AWS Global Accelerator. AWS Global Accelerator is a network layer service that directs traffic to optimal regional endpoints based on health, client location, and policies that you configure. It provides you with static IP addresses that you associate with your accelerator which will act as a fixed entry point to your application endpoints in one or more AWS Regions.

Global Accelerator uses the AWS global network to optimize the network path from your users to your applications, improving performance. It also monitors the health of your application endpoints and reacts instantly to changes in health or configuration. It will redirect user traffic to healthy endpoints that deliver the best performance and availability to your users.

In this lab, you will build upon the infrastructure you created in previous labs and use AWS Global Accelerator to route traffic between the Application Load Balancers in your primary and secondary regions.

![Placeholder for Arch Diagram](images/lab-1-arch.png)

Here's what you'll be doing:
- Create an Accelerator
- Add Listeners
- Add Endpoint Groups
- Add Endpoints
- Test your Accelerator

<a name="1"/>

### 1. Create an AWS Global Accelerator

- Open the Global Accelerator console at https://us-west-2.console.aws.amazon.com/ec2/v2/home?region=us-west-2#GlobalAcceleratorHome:
- Choose "Create accelerator" and  provide a name for your accelerator (AGAWorkshop)
- Choose "Next"

<kbd>![x](images/accelerator-name.png)</kbd>

### Add the listeners (TCP port 80), choose "Next"

<kbd>![x](images/add-listeners.png)</kbd>

### Add endpoint group (one per region in which you deployed the CloudFormation template), choose "Next"

<kbd>![x](images/add-endpoint-groups.png)</kbd>

### Add endpoints to the endpoint groups (choose in the drop down the Application Load Balancers the template created), then choose "Create accelerator"

<kbd>![x](images/add-endpoints.png)</kbd>

### The Accelerator will be "In progress" status, it takes about 5 minutes to move to "Deployed" status, you should be able to see the two static anycast IP addresses and the DNS assigned to the Accelerator

<kbd>![x](images/accelerator-inprogress.png)</kbd>

### Once the accelerator is in "Deployed" status, select the accelerator and make sure all the endpoints are healthy

<kbd>![x](images/accelerator-all-healthy.png)</kbd>

You should be able to access the application using the accelerator DNS.

<kbd>![x](images/accelerator-browser.png)</kbd>

AWS Global Accelerator can access public and private EC2 instances and load balancers. Note that you can't access the Application Load Balancers the CloudFormation created directly using their DNS, as they are internal load balancers, AWS Global Accelerator will access them using private IP addresses. This is the AWS Global Accelerator **origin cloaking** feature, for more information see: https://docs.aws.amazon.com/global-accelerator/latest/dg/introduction-benefits-of-migrating.html

# Checkpoint

You now have an operational workshop environment to work with. [Proceed to Lab 2](../lab-2-traffic-distribution)

## Participation

We encourage participation; if you find anything, please submit an [issue](https://github.com/aws-samples/aws-global-accelerator-workshop/issues). However, if you want to help raise the bar, submit a [PR](https://github.com/aws-samples/aws-global-accelerator-workshop/pulls)!
