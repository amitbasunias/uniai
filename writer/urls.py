from django.urls import path, re_path
from django.contrib.auth.views import LogoutView
from . import views
import uniai

urlpatterns = [
    path('', views.home),
    path('dashboard/', views.dashboard),
    path('login/', views.loginview),
    path('register/', views.register),
    path('write/', views.write),
    path('create/', views.create, name='create'),
    path('history/', views.history),
    path('package/', views.package),
    path('profile/', views.profile),
    path('getresult/', views.get_result),
 #   path('blog/', views.blog),
    path('logout/', LogoutView.as_view(), {'next_page': uniai.settings.LOGOUT_REDIRECT_URL}, name='logout'),

]


