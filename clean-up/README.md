# Multi-Region traffic management with AWS Global Accelerator - Clean up!

## Workshop Progress
✅ [Lab 0: Workshop Initialization](../lab-0-init)

✅ [Lab 1: Create your first AWS Global Accelerator](../lab-1-create-aws-global-accelerator)

✅ [Lab 2: Implement Intelligent Traffic Distribution](../lab-2-traffic-distribution)

✅ [Lab 3: Implement Fine-grained traffic control](../lab-3-fine-grained-control)

✅ [Lab 4: Implement Client Affinity](../lab-4-client-affinity)

✅ [Lab 5: Continuous availability monitoring and Failover](../lab-5-observability)

✅ [Bonus Lab: CloudWatch metrics and enabling flow logs](../bonus-lab)

**[Clean up](../clean-up)**

## Clen up the environment

### Disable and then delete the Accelerator

1. [Go to AWS Global Accelerator console](https://us-west-2.console.aws.amazon.com/ec2/v2/home?region=us-west-2#GlobalAcceleratorDashboard:)
2. *Select the accelerator > Delete > Disable accelerator > Enter **delete** in the text box > Click on Delete*
3. Delete the CloudFormation stack in **ALL the regions** you have created them.
