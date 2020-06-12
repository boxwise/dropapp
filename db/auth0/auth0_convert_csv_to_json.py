import json
import pandas as pd
import os


def create_entry(raw_entry,hashfunction,encoding):
    return_dict = {}
    return_dict['user_id']=str(raw_entry['id'])
    return_dict['name']=raw_entry['naam']
    return_dict['email']=raw_entry['email']
    return_dict['email_verified']=False
    return_dict['custom_password_hash']= {
        "algorithm":hashfunction, 
        "hash":{
            "value":raw_entry['pass'],
            "encoding":encoding
        }
    }
    app_metadata = {'is_god':raw_entry['is_admin']}
    if not pd.isna(raw_entry['cms_usergroups_id']):
        app_metadata['usergroup_id']=int(round(raw_entry['cms_usergroups_id']))
    if not pd.isna(raw_entry['valid_firstday']) and raw_entry['valid_firstday'] != '0000-00-00':
        app_metadata['valid_firstday']=raw_entry['valid_firstday']
    if not pd.isna(raw_entry['valid_lastday']) and raw_entry['valid_lastday'] != '0000-00-00':
        app_metadata['valid_lastday']=raw_entry['valid_lastday']   
    return_dict['app_metadata'] =app_metadata
    return return_dict

# set wd to path of current file
os.chdir(os.path.dirname(os.path.abspath(__file__)))
# load users for auth0 connection
users = pd.read_csv('users.csv')
usersdict = users[['id','email','pass','naam','cms_usergroups_id','is_admin','valid_firstday','valid_lastday']].T.to_dict()
# convert to auth0 readable data
auth0_dict = {}
for key in usersdict:
    auth0_dict[key]=create_entry(usersdict[key],"md5","hex")
# convert to json
auth0_list = [x for x in auth0_dict.values()]
json_users = json.dumps(auth0_list[:])
# export json
with open("users.json","w") as file:
    file.write(json_users)