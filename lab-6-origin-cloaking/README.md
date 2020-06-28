# Multi-Region traffic management with AWS Global Accelerator - Origin Cloaking

## Workshop Progress
✅ [Lab 0: Workshop Initialization](../lab-0-init)

✅ [Lab 1: Create your first AWS Global Accelerator](../lab-1-create-aws-global-accelerator)

✅ [Lab 2: Implement Intelligent Traffic Distribution](../lab-2-traffic-distribution)

✅ [Lab 3: Implement Fine-grained traffic control](../lab-3-fine-grained-control)

✅ [Lab 4: Implement Client Affinity](../lab-4-client-affinity)

✅ [Lab 5: Continuous availability monitoring and Failover](../lab-5-observability)

**[Lab 6: Origin Cloaking](../lab-6-origin-cloaking)**

[Bonus Lab: CloudWatch metrics and enabling flow logs](../bonus-lab)

[Cleaning up](../clean-up)

## Lab 6 - Origin Cloaking

The CloudFormation template we used in [Lab 0](../lab-0-init) created public subnets and internet facing Application Load Balancers, you can access these ALBs directly from any region using their DNS. For example let's try to access directly the Tokyo ALB from our four clients:

<kbd>![x](images/alb-origin-directly.png)</kbd>

As you can see we can access the ALB directly from any client. Our application is currently exposed to 5 different access points (the 4 ALBs and the Global Accelerator endpoint), this exposes it to distributed denial of service (DDoS) attacks and does not allow you to have control over how your end users reach the application. AWS Global Accelerator offers a feature to obfuscate the source origin through functionality commonly referred to as **origin cloaking,** allowing private ALBs and private EC2 instances to be accessed through Global Accelerator in a secure and simplified manner.

Origin cloaking allows you to make Global Accelerator the single internet-facing access point for your applications running in a single or multiple AWS Regions. The applications are centrally protected from distributed denial of service (DDoS) attacks through AWS Shield. You can also have greater control over how your end users reach your applications.

Let's protect one of our ALBs from being accessed directly using it DNS, I choose the Tokyo ALB. For this we can make the Route table associated to the two Subnet the CloudFormation created private by removing the Route to the Internet Gateway:

<details>
<summary>Learn more: Accessing different types of endpoints</summary>

AWS Global Accelerator can access public and private EC2 instances and load balancers. Note that you can't access the Application Load Balancers the CloudFormation created directly using their DNS, as they are internal load balancers, AWS Global Accelerator will access them using private IP addresses. This is the AWS Global Accelerator **origin cloaking** feature, for more information see: [AWS Global Accelerator Use Cases](https://docs.aws.amazon.com/global-accelerator/latest/dg/introduction-benefits-of-migrating.html)

</details>

# Checkpoint

At this point, you have created the workshop infrastructure as well as a simple AWS Global Accelerator. We can now take a deeper look into the details of AWS Global Accelerator! [Bonus lab](../bonus-lab)
