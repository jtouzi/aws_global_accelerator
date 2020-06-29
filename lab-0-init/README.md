# Multi-Region traffic management with AWS Global Accelerator - Workshop Initialization

## Workshop Progress
**[Lab 0: Workshop Initialization](../lab-0-init)**

[Lab 1: Create your first AWS Global Accelerator](../lab-1-create-aws-global-accelerator)

[Lab 2: Implement Intelligent Traffic Distribution](../lab-2-traffic-distribution)

[Lab 3: Implement Fine-grained traffic control](../lab-3-fine-grained-control)

[Lab 4: Implement Client Affinity](../lab-4-client-affinity)

[Lab 5: Continuous availability monitoring and Failover](../lab-5-observability)

[Lab 6: Origin Cloaking](../lab-6-origin-cloaking)

[Lab 7: AWS Global Accelerator Performance](../lab-7-aga-performance)

[Bonus Lab: CloudWatch metrics and enabling flow logs](../bonus-lab)

[Clean up](../clean-up)

## Lab 0 - Workshop Initialization

In this lab, you'll launch the core infrastructure for the workshop via AWS CloudFormation. We'll have a section to explain what you're launching here, so don't worry. After the workshop, simply delete the CloudFormation stack to delete all the above resources. We have a cleanup section at the end as well to remind you.

<a name="1"/>

### 1. Deploy the CloudFormation Stack

Launch the CloudFormation stack in **two or more AWS Regions** of your choice, note down the different regions you choose.

The link will load the CloudFormation Dashboard and start the stack creation process in the chosen region. The CloudFormation template will create the following resources:
- a VPC with an Internet Gateway and two subnets
- a Lambda function
- an Application Load Balancer with the Lambda function as target
- an IAM role the Lambda service will assume
- a permission to the Application Load Balancer to invoke the Lambda function

| Region | Launch Template |
|------- | -------- |
| Oregon (us-west-2) | [![Launch stack in Oregon](images/deploy-to-aws.png)](https://console.aws.amazon.com/cloudformation/home?region=us-west-2#/stacks/new?stackName=AGAWorkshop&templateURL=https://jtouzi.s3.amazonaws.com/GlobalAccelerator/AGAWorkshop.yaml) |
| Dublin (eu-west-1) | [![Launch stack in Dublin](images/deploy-to-aws.png)](https://console.aws.amazon.com/cloudformation/home?region=eu-west-1#/stacks/new?stackName=AGAWorkshop&templateURL=https://jtouzi.s3.amazonaws.com/GlobalAccelerator/AGAWorkshop.yaml) |
| Tokyo (ap-northeast-1) | [![Launch stack in Tokyo](images/deploy-to-aws.png)](https://console.aws.amazon.com/cloudformation/home?region=ap-northeast-1#/stacks/new?stackName=AGAWorkshop&templateURL=https://jtouzi.s3.amazonaws.com/GlobalAccelerator/AGAWorkshop.yaml) |
| Sydney (ap-southeast-2) | [![Launch stack in Sydney](images/deploy-to-aws.png)](https://console.aws.amazon.com/cloudformation/home?region=ap-southeast-2#/stacks/new?stackName=AGAWorkshop&templateURL=https://jtouzi.s3.amazonaws.com/GlobalAccelerator/AGAWorkshop.yaml) |
| Canada (ca-central-1) | [![Launch stack in Canada](images/deploy-to-aws.png)](https://console.aws.amazon.com/cloudformation/home?region=ca-central-1#/stacks/new?stackName=AGAWorkshop&templateURL=https://jtouzi.s3.amazonaws.com/GlobalAccelerator/AGAWorkshop.yaml) |
| Mumbai (ap-south-1) | [![Launch stack in Mumbai](images/deploy-to-aws.png)](https://console.aws.amazon.com/cloudformation/home?region=ap-south-1#/stacks/new?stackName=AGAWorkshop&templateURL=https://jtouzi.s3.amazonaws.com/GlobalAccelerator/AGAWorkshop.yaml) |

The template will automatically bring you to the CloudFormation Dashboard and start the stack creation process in the specified region. The default stack name **AGAWorkshop** (change it if you want to use a different name or if you want to deploy 2 endpoints in the same region), proceed through the wizard to launch the stack. Leave all options at their default values, but make sure to check the box to allow CloudFormation to create IAM roles on your behalf:

<kbd>![x](images/cfn-create-template.png)</kbd>

<kbd>![x](images/cfn-create.png)</kbd>

After you click on **Create stack**, you will have the following window, it takes 3 to 4 minutes for the stack to be created.

<kbd>![x](images/cfn-create-start.png)</kbd>

<kbd>![x](images/cfn-create-complete.png)</kbd>

For this workshop we will use **US-WEST-2 (Oregon), EU-WEST-1 (Dublin) and AP-NORTHEAST-1 (Tokyo) regions, I've created two endpoints in Oregon region.** 

<a name="2"/>

### 2. Familiarize yourself with the workshop environment

<kbd>![x](images/design.png)</kbd>

<a name="3"/>

### 3. Workshop tips

These tips will help you be more efficient and save time.

* If you have a tablet with you, use that for the workshop instructions while you work on your laptop.
* "Right-click, Open Link in New Tab" is your friend - throughout the workshop you'll be navigating to various service dashboards in the AWS management console and referring back to lab instructions. Using multiple browser tabs will save you time.
* Open a text editor to copy/paste resource names or keep a tab open with the CloudFormation outputs. For example, to load the Mythical Mysfits application, you'll browse to the load balancer DNS name. This value is good to have easily accessible to save you time since you'll be loading the app throughout the workshop.

# Checkpoint

You now have an operational workshop environment to work with. [Proceed to Lab 1](../lab-1-create-aws-global-accelerator)

## Participation

We encourage participation; if you find anything, please submit an [issue](https://github.com/aws-samples/aws-global-accelerator-workshop/issues). However, if you want to help raise the bar, submit a [PR](https://github.com/aws-samples/aws-global-accelerator-workshop/pulls)!
