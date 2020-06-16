Instructions for the instructor:

Use the CloudFormation template to create 4 EC2 instances in different regions (Ohio, Singapore, Paris and Sao Paulo) that will be used by the workshop participants to test their Accelerator settings.

Make sure you have a Key Pair in each of these regions (https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/ec2-key-pairs.html).

1. Create the stack in the regions with the following CLI commands (Download the EC2.yaml template, make sure you use Key Pairs in your account):

$ aws cloudformation create-stack --stack-name AGAEC2 --template-body file://EC2.yaml --parameters ParameterKey=KeyName,ParameterValue=YourSaoPauloKey --region sa-east-1

$ aws cloudformation create-stack --stack-name AGAEC2 --template-body file://EC2.yaml --parameters ParameterKey=KeyName,ParameterValue=YourParisKey --region eu-west-3

$ aws cloudformation create-stack --stack-name AGAEC2 --template-body file://EC2.yaml --parameters ParameterKey=KeyName,ParameterValue=YourSingaporeKey --region ap-southeast-1

$ aws cloudformation create-stack --stack-name AGAEC2 --template-body file://EC2.yaml --parameters ParameterKey=InstanceType,ParameterValue=t3.medium ParameterKey=KeyName,ParameterValue=YourOhioKey --region us-east-2

2. Get the IP addresses of the EC2 instances and update the index.php page

$ aws cloudformation describe-stacks --stack-name AGAEC2 --query "Stacks[0].Outputs[?OutputKey=='PublicIp'].OutputValue" --output text --region us-east-2
$ aws cloudformation describe-stacks --stack-name AGAEC2 --query "Stacks[0].Outputs[?OutputKey=='PublicIp'].OutputValue" --output text --region ap-southeast-1
$ aws cloudformation describe-stacks --stack-name AGAEC2 --query "Stacks[0].Outputs[?OutputKey=='PublicIp'].OutputValue" --output text --region eu-west-3
$ aws cloudformation describe-stacks --stack-name AGAEC2 --query "Stacks[0].Outputs[?OutputKey=='PublicIp'].OutputValue" --output text --region sa-east-1
