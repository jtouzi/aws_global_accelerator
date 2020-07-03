## Instructions for the workshop instructor

Use the CloudFormation template in this section (EC2.yaml) to create 4 EC2 instances in different regions (Ohio, Singapore, Paris and Sao Paulo) that will be used by the workshop participants to test their Accelerator settings.

Make sure you have a Key Pair in each of these regions (https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/ec2-key-pairs.html).

### Create stacks in the regions with the CLI commands below

Download the [ec2.yaml](cfn/ec2.yaml) template, make sure you use Key Pairs in your account.

```
$ aws cloudformation create-stack --stack-name AGAEC2 --template-body file://ec2.yaml --parameters ParameterKey=KeyName,ParameterValue=YourSaoPauloKey --region sa-east-1

$ aws cloudformation create-stack --stack-name AGAEC2 --template-body file://ec2.yaml --parameters ParameterKey=KeyName,ParameterValue=YourParisKey --region eu-west-3

$ aws cloudformation create-stack --stack-name AGAEC2 --template-body file://ec2.yaml --parameters ParameterKey=KeyName,ParameterValue=YourSingaporeKey --region ap-southeast-1

$ aws cloudformation create-stack --stack-name AGAEC2 --template-body file://ec2.yaml --parameters ParameterKey=InstanceType,ParameterValue=t3.medium ParameterKey=KeyName,ParameterValue=YourOhioKey --region us-east-2
```

### Get the IP addresses of the EC2 instances
```
$ aws cloudformation describe-stacks --stack-name AGAEC2 --query "Stacks[0].Outputs[?OutputKey=='PublicIp'].OutputValue" --output text --region us-east-2

$ aws cloudformation describe-stacks --stack-name AGAEC2 --query "Stacks[0].Outputs[?OutputKey=='PublicIp'].OutputValue" --output text --region ap-southeast-1

$ aws cloudformation describe-stacks --stack-name AGAEC2 --query "Stacks[0].Outputs[?OutputKey=='PublicIp'].OutputValue" --output text --region eu-west-3

$ aws cloudformation describe-stacks --stack-name AGAEC2 --query "Stacks[0].Outputs[?OutputKey=='PublicIp'].OutputValue" --output text --region sa-east-1
```

### Update [index.php](index.php) file with these EC2 instance IPs or DNSs (e.g.)
```
$EC2_SaoPaulo = "18.231.143.234";
$EC2_Paris = "15.236.45.221";
$EC2_Singapore = "54.52.197.134";
$EC2_Ohio = "18.188.56.13";
```

### Upload the index.php file in us-east-2 region
```
scp -i /path/to/Key/YourOhioKey.pem index.php ec2-user@OHIO_IP:/var/www/html/
```

### Share the Ohio DNS to the participants so they can test their accelerator settings

```
$ aws cloudformation describe-stacks --stack-name AGAEC2 --query "Stacks[0].Outputs[?OutputKey=='URL'].OutputValue" --output text --region us-east-2
```

### Clean up AT THE END of the workshop

```
$ aws cloudformation delete-stack --stack-name AGAEC2 --region us-east-2
$ aws cloudformation delete-stack --stack-name AGAEC2 --region sa-east-1
$ aws cloudformation delete-stack --stack-name AGAEC2 --region eu-west-3
$ aws cloudformation delete-stack --stack-name AGAEC2 --region ap-southeast-1
```
