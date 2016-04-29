#!/usr/bin/python
#-*- coding: UTF-8 -*-
#51talk auto meal script

import HTMLParser
import urlparse
import urllib
import urllib2
import cookielib
import string
import re
import time

__author__ = 'xiuran.li'

#订餐系统用户名
username = 'lixiuran'
#订餐系统密码
password = 'qq139547'

#登录的主页面
hosturl = 'http://172.16.0.33:90/meal/login'
#登录提交地址
posturl = 'http://172.16.0.33:90/meal/doLogin'
#订餐提交地址
mealurl = 'http://172.16.0.33:90/meal/addMeal'

cj = cookielib.LWPCookieJar()
cookie_support = urllib2.HTTPCookieProcessor(cj)
opener = urllib2.build_opener(cookie_support, urllib2.HTTPHandler)
urllib2.install_opener(opener)

h = urllib2.urlopen(hosturl)

headers = {
'User-Agent' : 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:14.0) Gecko/20100101 Firefox/14.0.1',
'Referer' : 'http://172.16.0.33:90/meal/'
}

#构造Post数据
postData = {'username' : username, 'password' : password}

#需要给Post数据编码
postData = urllib.urlencode(postData)

#完成登录过程
request = urllib2.Request(posturl, postData, headers)
response = urllib2.urlopen(request)
text = response.read()
print 'Login Status : ' + text

###################### 开始订餐了 #########################################

today = time.strftime("%Y-%m-%d", time.localtime())

#构造订餐Post数据
postData2 = {'meal_date' : today}

#需要给Post数据编码
postData2 = urllib.urlencode(postData2)

#订餐操作
request = urllib2.Request(mealurl, postData2, headers)
response = urllib2.urlopen(request)
text = response.read()
print 'Meal Status : ' + text
print 'Today : ' + today + ' | username : ' + username
