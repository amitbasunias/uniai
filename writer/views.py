from django.shortcuts import render, redirect
from django.contrib import messages
from django.conf import settings
from django.contrib.auth import login, authenticate
from django.contrib.auth.forms import AuthenticationForm
from django.contrib.auth.decorators import login_required
from django.views.generic import ListView
from .beta import *

from django.http import JsonResponse, HttpResponse
import json, string, random
import stripe

from .beta import *
from .creativity import *
from .utone import *
from .functions import *
import openai

OPENAI_API_KEY = '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'

openai.api_key = OPENAI_API_KEY
from .forms import NewUserForm
from .models import packages, notes, UserAcc

# Create your views here.


def home(request):

    model = packages
    template_name = "home.html"


    p_list = packages.objects.all()
    #def get_queryset(self):
        #return packages.objects.all()

    return render(request,'home.html',{
        'p_list': p_list,
    })
@login_required(login_url='/login/')
def dashboard(request):
    return render(request, 'dash.html')

@login_required(login_url='/login/')
def package(request):

    p_list = packages.objects.all()
    return render(request, 'package.html', {
        'p_list': p_list,
    })


def profile(request):
    return render(request, 'profile.html')

@login_required(login_url='/login/')
def history(request):
    view_note = notes.objects.filter(owner=request.user)

    return render(request, 'history.html', { 'view_note': view_note,})

def notes_view(request,notes_id):
    edit_note = notes.objects.get(id=notes_id)
    print(edit_note.text)

    return render(request, "notes.html",{ 'edit_note': edit_note,})


def loginview(request):

    if request.method == "POST":

        form_class = AuthenticationForm(request.POST, request.POST)
        if form_class.is_valid():
            username = form_class.cleaned_data['username']
            password = form_class.cleaned_data['password']

            user = authenticate(username=username, password=password)

            login(request, user)
            return redirect('/dashboard')
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

@login_required(login_url='/login/')
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

def create(request):

    if request.method =='POST':

        get_result(request)

        #usertile = request.POST.get('usertitle')
        #usertext = request.POST.get('userprompt')
        #usertone = request.POST.get('tone')
        #creativeness = request.POST.get('creativity')
        #qa = request.POST.get('qa')
        #aa = request.POST.get('aa')
        #qb = request.POST.get('qb')
        #num = request.POST.get('num')
        #creatives= hello(creativeness)
        #tone=utone(usertone)
        #print(usertext)
        #print(tone)
        #print(creatives)
        #aioutput= direction(usertile, usertext, tone, creatives, qa, aa, qb, num)
        #print(aioutput)
        #print(type(aioutput))

        #data = { "aioutput": aioutput,}
        #return HttpResponse(json.dumps(data))

        #if request.method =='GET':
            #return JsonResponse(data)
    return render(request, 'indexsec.html')

def get_result(request):
    user_email = request.user.email

    if request.method =='POST':
        json_req = json.loads(request.body.decode('utf-8'))
        print(json_req)
        print(json_req['text'])

        #print("json text: ", request.body.get('text'))
        usertile = json_req['title']
        usertext = json_req['text']
        usertone = json_req['tone']
        creativeness = json_req['creativity']
        qa = json_req['qa']
        aa = json_req['aa']
        qb = json_req['qb']
        num = json_req['num']
        creatives= hello(creativeness)
        tone=utone(usertone)
        aioutput= direction(usertile, usertext, tone, creatives, qa, aa, qb, num)
        print(type(aioutput))
        note_title = aioutput[:15]+"..."
        if aioutput:
            current_user = UserAcc.objects.get(email=user_email)
            if (current_user.credit >= len(aioutput.split())):
                current_user.credit = current_user.credit - len(aioutput.split())
                current_user.save()
            else:
                list_output = aioutput.split()
                list_output = list_output[:current_user.credit]
                aioutput = ' '.join(list_output)
                current_user.credit = current_user.credit - len(aioutput.split())
                current_user.save()
            
            note = notes.objects.create(owner=request.user,title=note_title, text=aioutput)

    data = { "aioutput": aioutput, 'word_length': len(aioutput.split()), }


    return JsonResponse(data, safe=False)

def checkout(request, packages_id):
    p_list = packages.objects.all()
    stripe.api_key = settings.STRIPE_SECRET_KEY
    domain_url = "https://afikur-rahman1-refactored-computing-qgp6r5x9prj3gj7-8000.preview.app.github.dev/"
    line_item = []

    for pack in p_list:
        if(packages_id==pack.id):
            line_item = [{
                'name': str(pack.name),
                'quantity': 1,
                'description':str(pack.desc1),
                'currency': 'usd',
                'amount': str(pack.price)+"00",

            }]
    payment_key = ''.join(random.choices(string.ascii_uppercase +
                             string.digits, k=24))
    request.session['key'] = payment_key
    checkout_session = stripe.checkout.Session.create(
                success_url=domain_url + 'dashboard/'+payment_key+'/'+str(packages_id),
                cancel_url=domain_url + 'cancelled/',
                payment_method_types=['card'],
                mode='payment',
                line_items=line_item)

    
    return redirect(checkout_session.url)

def package_purchase(request, package_key, packages_id):
        package_values = {
            '1': 50000,
            '2': 150000,
        }

        if(package_key == request.session['key']):
            current_user = UserAcc.objects.get(email=request.user.email)
            current_user.credit = current_user.credit + package_values[packages_id]
            current_user.save()


        return render(request, 'dash.html')