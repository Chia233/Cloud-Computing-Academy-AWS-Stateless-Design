import json
import boto3
import botocore
import random

def lambda_handler(event, context):
    getETag = event['Records'][0]['s3']['object']['eTag']
    getName = event['Records'][0]['s3']['object']['key']
    
    dynamodb = boto3.resource('dynamodb')
    table = dynamodb.Table('MD5Table')
    
    #try to retrieve ETag from dynamoDB 
    response = table.get_item(
        Key = {
            'Hash': getETag
        }
    )
    
    #if nothing can be retrieved(its not in the table), add it
    if 'Item' not in response :
        response2 = table.put_item(
            Item = {
            'Hash': getETag
            }
        )

        #first, test to see if there is already a file with the same name in pernament storage
        
        #establish connection
        s3Conn = boto3.resource('s3')
        testing = s3Conn.Bucket('ccaworkstorage')
        
        s3 = boto3.client('s3')
        
        #establish target and source bucket
        source_bucket = event['Records'][0]['s3']['bucket']['name']
        target_bucket = 'ccaworkstorage'
        copy_source = {'Bucket':source_bucket, 'Key':getName}
        
        #list out objects to check if the bucket is empty or not
        listObject = s3.list_objects_v2(Bucket= target_bucket, Prefix= getName)
        if listObject['KeyCount'] == 0:
            print ("The bucket is actually empty, now adding the first object")
            #then send it to another s3 bucket for pernament storage
            try:
                print ("Waiting for source file to persist in the source bucket")
                waiter = s3.get_waiter('object_exists')
                waiter.wait(Bucket=source_bucket, Key=getName)
                print ("Copying from source s3 bucket to new s3 bucket")
                s3.copy_object(Bucket=target_bucket, Key=getName, CopySource=copy_source)
            except Exception as e:
                print (e)
                print ('error getting object from bucket')
                raise e
        else:
            for attribute in testing.objects.all():
                if attribute.key == getName:
                    print ("There is a match with " + attribute.key)
                    #then change key string and send it to another s3 bucket for pernament storage
                    newkey = str(random.randint(1,99999)) + str(getName)
                    try:
                        print ("Waiting for source file to persist in the source bucket")
                        waiter = s3.get_waiter('object_exists')
                        waiter.wait(Bucket=source_bucket, Key=getName)
                        print ("Copying from source s3 bucket to new s3 bucket")
                        s3.copy_object(Bucket=target_bucket, Key=newkey, CopySource=copy_source)
                    except Exception as e:
                        print (e)
                        print ('error getting object from bucket')
                        raise e
            else:
                print ("There is no key that matches with " + attribute.key)
                #then send it to another s3 bucket for pernament storage
                try:
                    print ("Waiting for source file to persist in the source bucket")
                    waiter = s3.get_waiter('object_exists')
                    waiter.wait(Bucket=source_bucket, Key=getName)
                    print ("Copying from source s3 bucket to new s3 bucket")
                    s3.copy_object(Bucket=target_bucket, Key=getName, CopySource=copy_source)
                except Exception as e:
                    print (e)
                    print ('error getting object from bucket')
                    raise e
    else:
        #if it is a match, update table with duplicate = True
        table.update_item(
            Key = {
                    'Hash':getETag
                },
            UpdateExpression='SET Duplicate = :value',
            ExpressionAttributeValues={
                ':value': True
            }
        )
        #if it is a match, send SNS topic target ARN
        client = boto3.client(
        "sns",
        aws_access_key_id="access key here",
        aws_secret_access_key="secret key here",
        region_name="us-east-1"
        )
        
        client.publish(
        TargetArn="arn here",
        Message="There is a duplicate in the system!",
        Subject="Duplicate Alert"
        )