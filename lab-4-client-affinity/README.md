# Multi-Region traffic management with AWS Global Accelerator - Client Affinity

## Workshop Progress
✅ [Lab 0: Workshop Initialization](../lab-0-init)

✅ [Lab 1: Create your first AWS Global Accelerator](../lab-1-create-aws-global-accelerator)

✅ [Lab 2: Implement Intelligent Traffic Distribution](../lab-2-traffic-distribution)

✅ [Lab 3: Implement Fine-grained traffic control](../lab-3-fine-grained-control)

**[Lab 4: Implement Client Affinity](../lab-4-client-affinity)**

[Lab 5: Continuous availability monitoring and Failover](../lab-5-observability)

[Lab 6: Implement Origin Cloaking](../lab-6-origin-cloaking)

[Lab 7: AWS Global Accelerator Performance](../lab-7-aga-performance)

[Bonus Lab: CloudWatch metrics and enabling flow logs](../bonus-lab)

[Clean up](../clean-up)

## Lab 4 - Client Affinity

By default AWS Global Accelerator distributes traffic equally between the endpoints in the endpoint groups for the listener. If you have stateful applications, you can choose to have Global Accelerator direct all requests from a user at a specific source (client) IP address to the same endpoint resource, to maintain client affinity. You do this by changing the **Client Affinity** from **None** (default) to **Source IP** for the listener.

<details>
<summary>Learn more: Client Affinity</summary>

Global Accelerator uses a consistent-flow hashing algorithm to choose the optimal endpoint for a user's connection. For more information, see our [documentation](https://docs.aws.amazon.com/global-accelerator/latest/dg/about-listeners.html#about-listeners-client-affinity)

</details>

<kbd>![x](images/client-affinity.png)</kbd>

Let's see how AWS Global Accelerator will handle requests from Sao Paulo and Ohio.

<kbd>![x](images/sao-paulo-client-affinity.png)</kbd>

### Comments

US-WEST-2 has two endpoints, requests from Sao Paulo and Ohio have been processed by only 1 endpoint in the endpoint group because of the client affinity.

<a name="lab6"/>

# Checkpoint

Now that we have implemented fine-grained traffic control and Client Affinity, let's see how Global Accelerator handles failover. When you're ready [proceed to Lab 5](../lab-5-observability)

## Participation

We encourage participation; if you find anything, please submit an [issue](https://github.com/aws-samples/aws-global-accelerator-workshop/issues). However, if you want to help raise the bar, submit a [PR](https://github.com/aws-samples/aws-global-accelerator-workshop/pulls)!
