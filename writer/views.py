from django.shortcuts import render, redirect
from .beta import *
from .creativity import *
from .utone import *
# Create your views here.
def home(request):
    return render(request, 'home.html')

def dashboard(request):
    return render(request, 'dash.html')

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
    if request.method == 'POST':
        usertile = request.POST.get('usertitle')
        usertext = request.POST.get('userprompt')
        usertone = request.POST.get('tone')
        creativeness = request.POST.get('creativity')
        creativity= creativity(creativeness)
        tone=utone(usertone)
        prompt = f" Write a detailed, very long blog article using the following details. Make sure to add conclusion: \r\n Title: {usertile} \n Keywords: {usertext}\r\n{tone} Blog article: \n"
        print(prompt)
        response = openai.Completion.create(
            engine="text-davinci-002",
            prompt=prompt,
            temperature=creativity,
            max_tokens=3400,
            top_p=1,
            frequency_penalty=0,
            presence_penalty=0
        )
        if 'choices' in response:
            answer = response['choices'][0]['text']
        return answer
    return render(request, 'indexsec.html')

def blog(request):


    prompt= f" Write a detailed, very long blog article using the following details. Make sure to add conclusion: \r\n Title: {usertile} \n Keywords: {usertext}\r\n{tone} Blog article: \n"
    print(prompt)
    response = openai.Completion.create(
        engine="text-davinci-002",
        prompt=prompt,
        temperature=creativity,
        max_tokens=3400,
        top_p=1,
        frequency_penalty=0,
        presence_penalty=0
    )
    if 'choices' in response:
        answer= response['choices'][0]['text']
    return answer