from django.shortcuts import render, redirect
from django.contrib import messages
from django.contrib.auth import login, authenticate
from django.contrib.auth.forms import AuthenticationForm
from django.views.generic import ListView
from .beta import *
from .forms import NewUserForm
from .models import packages

# Create your views here.
def home(request):

    model = packages
    template_name = "home.html"

    test_var = "This is a test"

    p_list = packages.objects.all()
    #def get_queryset(self):
        #return packages.objects.all()

    return render(request,'home.html',{
        'test_var': test_var,
        'p_list': p_list,
    })

def dashboard(request):
    return render(request, 'dash.html')

def loginview(request):
    
    if request.method == "POST":

        form_class = AuthenticationForm(request.POST, request.POST)
        if form_class.is_valid():
            username = form_class.cleaned_data['username']
            password = form_class.cleaned_data['password']

            user = authenticate(username=username, password=password)

            login(request, user)
            return redirect('/')
        else:
            print("login invalid")
            print(form_class.errors)
    #if request.method == 'POST':
        #email = request.POST.get('email')
        #password = request.POST.get('password')
        #return redirect('/dashboard')
    return render(request, template_name='login.html')

def register(request):
    if request.method == "POST":
        form = NewUserForm(request.POST)     
        if form.is_valid():
            user = form.save()
            login(request, user)
            messages.success(request, "Sign Up Successful")
            return redirect("/")
        print(form.errors)
        messages.error(request, "Unsuccessful registration. Invalid information.")
    form = NewUserForm()

    return render(request, 'register.html')

def write(request):
    if request.method == 'POST':

        details = request.POST.get('details')
        language = request.POST.get('language')
        category = request.POST.get('category')
        print(language + category + details)
        if category == 'headline':
            headprompt = f" write headline using following details: \n\n{details}  \nin {language} "
            blogA = headline(headprompt)
            blogExpandedA = blogA.replace('\n', '<br>')
        elif category == 'blogintro':
            headprompt = f" Expand the topic into a clever and creative blog introduction: \n\n {details} \n in {language} "
            blogA = headline(headprompt)
            blogExpandedA = blogA.replace('\n', '')
        if category == 'blogcon':
            headprompt = f" Expand the topic into a clever and creative blog conclusion:\n\n {details} \n in {language} "
            blogA = headline(headprompt)
            blogExpandedA = blogA.replace('\n', '<br>')
        elif category == 'blogpara':
            headprompt = f" Expand the topic into a clever and witty blog section: \n\n {details} \n in {language} "
            blogA = headline(headprompt)
            blogExpandedA = blogA.replace('\n', '<br>')
        elif category == 'translate':
            headprompt = f" Make a creative story about: \n\n {details} \n in {language} "
            blogA = headline(headprompt)
            blogExpandedA = blogA.replace('\n', '<br>')
        elif category == 'email':
            headprompt = f"Write video description from following details: \n\n {details} \n in {language} "
            blogA = headline(headprompt)
            blogExpandedA = blogA.replace('\n', '<br>')
        elif category == 'business':
            headprompt = f"White a product description about: \n\n {details} \n in {language} "
            blogA = headline(headprompt)
            blogExpandedA = blogA.replace('\n', '<br>')
        print(blogExpandedA)

    return render(request, 'write.html')