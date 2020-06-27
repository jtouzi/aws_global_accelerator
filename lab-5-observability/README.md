# Multi-Region traffic management with AWS Global Accelerator - Failover

## Workshop Progress
✅ [Lab 0: Workshop Initialization](../lab-0-init)

✅ [Lab 1: Create your first AWS Global Accelerator](../lab-1-create-aws-global-accelerator)

✅ [Lab 2: Implement Intelligent Traffic Distribution](../lab-2-traffic-distribution)

✅ [Lab 3: Implement Fine-grained traffic control](../lab-3-fine-grained-control)

✅ [Lab 4: Implement Client Affinity](../lab-4-client-affinity)

**[Lab 5: Continuous availability monitoring and Failover](../lab-5-observability)**

[Lab 6: Origin Cloaking](../lab-6-origin-cloaking)

[Bonus Lab: CloudWatch metrics and enabling flow logs](../bonus-lab)

[Cleaning up](../clean-up)

## Lab 5 - Continuous availability monitoring and Failover
AWS Global Accelerator regularly sends requests to endpoints to test their status. These health checks run automatically. The guidance for determining the health of each endpoint and the timing for the health checks depend on the type of endpoint resource.

For some reason, our endpoint in AP-NORTHEAST-1 stops responding and the Application Load Balancer health check fails. Once an endpoint is market as unhealthy, it takes Global Accelerator up to 30 seconds to direct new connections to the next closest endpoint in the same or another AWS Region.

To simulate the failure, change the response status code returned by the [Lambda function](https://ap-northeast-1.console.aws.amazon.com/lambda/) from 200 to 403 for example and Save.

<kbd>![x](images/lambda-function.png)</kbd>

After a maximum of 60 seconds (30 seconds for the ALB and 30 seconds for the Global Accelerator health chekcs), the endpoint status will become **Unhealthy** and Global Accelerator will start sending new connections automatically to the next available endpoint.

<kbd>![x](images/failover.png)</kbd>

Let's see how AWS Global Accelerator will handle requests from Singapore, normally processed by AP-NORTHEAST-1 (Tokyo) region.

<kbd>![x](images/tokyo-failover.png)</kbd>

### Comments
Requests from Singapore are now processed in EU-WEST-1 (Dublin) region. AWS Global Accelerator will continue to monitor the Tokyo endpoint, and will resume sending traffic to it once it becomes healthy.

### Checkpoint
Now that we know how AWS Global Accelerator manages traffic, how we can increase or decrease the percentage of traffic sent to an endpoint group (using traffic dials) or a specific endpoint in an endpoint group (using endpoint weights), how we can send traffic from the same clients to the same endpoints (using Client Affinity) and how failover works, let's protect our endpoints by allowing only Global Accelerator to access them. When you're ready [proceed to Lab 6](../lab-6-origin-cloaking)
