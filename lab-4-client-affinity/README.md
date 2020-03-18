# Mythical Mysfits: Multi-Region Control with AWS Global Accelerator

![mysfits-welcome](/images/mysfits-welcome.png)

## Workshop Progress
Placeholder

## Lab 4 - Client Affinity
If we want AWS Global Accelerator to direct all requests from a user at a specific source (client) IP address to the same endpoint resource (to maintain client affinity), we can change the "Client Affinity" from "None" (default) to "Source IP" for the listener.

<kbd>![x](./img/client-affinity.png)</kbd>

Let's see how AWS Global Accelerator will handle requests from Herndon.

<kbd>![x](./img/herndon-client-affinity.png)</kbd>

### Comments

US-WEST-2 has two endpoints, but only one processed the 100 requests because they were all from the same IP address.

### Resources
Client Affinity: https://docs.aws.amazon.com/global-accelerator/latest/dg/about-listeners.html#about-listeners-client-affinity

<a name="lab6"/>
