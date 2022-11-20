from django.urls import path
from . import views

urlpatterns = [
    path('', views.home ),
    path('dashboard/', views.dashboard),
    path('login/', views.login),
    path('register/', views.register),
    path('write/', views.write),
    path('create/', views.create),
    path('history/', views.history),
    path('package/', views.package),
    path('profile/', views.profile),
 #   path('blog/', views.blog),
]