from django.db import models
from django.contrib.auth.models import AbstractBaseUser, PermissionsMixin

from .managers import CustomUserManager
# Create your models here.

class UserAcc(AbstractBaseUser, PermissionsMixin):
    firstname = models.CharField(max_length=30)
    lastname = models.CharField(max_length=30)
    email = models.EmailField(unique=True, primary_key=True)
    password = models.CharField(max_length=20)
    credit = models.IntegerField(default=0)
    is_active = models.BooleanField(default=True)
    is_staff = models.BooleanField(default=False)
    
    USERNAME_FIELD = 'email'
    REQUIRED_FIELDS: list['firstname','lastname','email','password']

    objects = CustomUserManager()

    def __str__(self):
        return self.email

class packages(models.Model):
    name = models.CharField(max_length=20)
    value = models.IntegerField(default=0)
    price = models.CharField(max_length=10,default='')
    cycle = models.CharField(max_length=20,default='')
    limited_offer_desc = models.CharField(max_length=150, blank=True)
    desc1 = models.CharField(max_length=150)
    desc2 = models.CharField(max_length=150)

    def __str__(self):
        return self.name
