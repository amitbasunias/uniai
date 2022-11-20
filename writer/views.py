from django.shortcuts import render, redirect
from .beta import *

from .creativity import *
from .utone import *
from .functions import *
import openai

OPENAI_API_KEY = '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'

openai.api_key = OPENAI_API_KEY
# Create your views here.
def home(request):
    return render(request, 'home.html')

def dashboard(request):
    return render(request, 'dash.html')

def package(request):
    return render(request, 'package.html')

def profile(request):
    return render(request, 'profile.html')


def history(request):
    return render(request, 'history.html')

def login(request):
    if request.method == 'POST':
        email = request.POST.get('email')
        password = request.POST.get('password')
        return redirect('/dashboard')
    return render(request, 'login.html')

def register(request):
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
            blogA = beta.headline(headprompt)
            blogExpandedA = blogA.replace('\n', '')
        if category == 'blogcon':
            headprompt = f" Expand the topic into a clever and creative blog conclusion:\n\n {details} \n in {language} "
            blogA = beta.headline(headprompt)
            blogExpandedA = blogA.replace('\n', '<br>')
        elif category == 'blogpara':
            headprompt = f" Expand the topic into a clever and witty blog section: \n\n {details} \n in {language} "
            blogA = beta.headline(headprompt)
            blogExpandedA = blogA.replace('\n', '<br>')
        elif category == 'translate':
            headprompt = f" Make a creative story about: \n\n {details} \n in {language} "
            blogA = beta.headline(headprompt)
            blogExpandedA = blogA.replace('\n', '<br>')
        elif category == 'email':
            headprompt = f"Write video description from following details: \n\n {details} \n in {language} "
            blogA = beta.headline(headprompt)
            blogExpandedA = blogA.replace('\n', '<br>')
        elif category == 'business':
            headprompt = f"White a product description about: \n\n {details} \n in {language} "
            blogA = beta.headline(headprompt)
            blogExpandedA = blogA.replace('\n', '<br>')
        print(blogExpandedA)

    return render(request, 'write.html')
def create(request):
    if request.method =='POST':
        usertile = request.POST.get('usertitle')
        usertext = request.POST.get('userprompt')
        usertone = request.POST.get('tone')
        creativeness = request.POST.get('creativity')
        qa = request.POST.get('qa')
        aa = request.POST.get('aa')
        qb = request.POST.get('qb')
        num = request.POST.get('num')
        creatives= hello(creativeness)
        tone=utone(usertone)
        print(usertext)
        print(tone)
        print(creatives)
        aioutput= direction(usertile, usertext, tone, creatives, qa, aa, qb, num)
        print(aioutput)
    return render(request, 'indexsec.html')
