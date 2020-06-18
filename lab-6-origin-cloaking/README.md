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

The CloudFormation template we used in Lab 1 created private subnets and internal Application Load Balancers, you can't access them directly by using their DNS, you must use the Global Accelerator endpoint to access the application.


<details>
<summary>Learn more: Accessing different types of endpoints</summary>

AWS Global Accelerator can access public and private EC2 instances and load balancers. Note that you can't access the Application Load Balancers the CloudFormation created directly using their DNS, as they are internal load balancers, AWS Global Accelerator will access them using private IP addresses. This is the AWS Global Accelerator **origin cloaking** feature, for more information see: [AWS Global Accelerator Use Cases](https://docs.aws.amazon.com/global-accelerator/latest/dg/introduction-benefits-of-migrating.html)

</details>

# Checkpoint

At this point, you have created the workshop infrastructure as well as a simple AWS Global Accelerator. We can now take a deeper look into the details of AWS Global Accelerator! [Bonus lab](../bonus-lab)
