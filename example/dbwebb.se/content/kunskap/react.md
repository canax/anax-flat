---
author: ceruza
revision:
    2016-11-21: (A, ceruza) Första Versionen
category:
    - react
    - javascript
    - JSX
    - flux
    - redux
...

React - Och allt det andra
===================================

Den viktigaste insikten du kommer göra när du börjar lära dig React är att React bara är ett renderings-bibliotek - inte ett ramverk i stil med Mithril eller AngularJS. Om du vill utveckla appar som är baserade på react måste du räkna med att lära dig ett flertal teknologier - som tur är blomstrar Reacts ekosystem, och du kan avnjuta bibliotek som Redux och React-router, byggsystem som Webpack och transpilatorer som Babel.

Det här är huvudsakligen en teoretisk artikel som kommer ligga som grund för ytterligare artiklar som behandlar React och närliggande tekniker. Det kan vara en tankefälla att tänka sig React som en en övergripande helhet, i verkligheten kom Flux först, och det är den underliggande strukturen som React ofta använder i olika former. Det kan vara värdefullt att förstå varför Flux och React skapades, bland annat för att det låter dig bli en bättre utvecklare och även för att det låter dig förstå när React är ett bra val, och när det inte är det.

MVC brukar kallas "The King of Compound Patterns", och har varit en draghäst i webbutvecklingssammanhang länge - om Flux/Redux kommer leda till ett paradigmskifte är det för tidigt att avgöra idag, men det är oneklingen populärt, och även om så inte sker kan det finnas ett värde i att arbeta med ett alternativ till MVC för att bättre förstå dess brister och styrkor.

* Flux, den bakomliggande strukturen i React
* Skillanden mellan Flux och MV*
* React, den totala abstraktionen
* JSX
* Babel och ES2015
* Hello World React



Förutsättning {#pre}
-------------------------------

Du har minst arbetat dig igenom följande artiklar, och har en mer än grundläggande förståelse för JavaScript som inkluderar callbacks, objekthantering osv, samt grundläggande kunskap om MVC. Oroa dig dock inte om du blir förvirrad, artikeln täcker mycket information som är bra att veta när man arbetar med React, men du behöver inte förstå allt direkt. Mycket kommer falla på plats när du börjat skriva React-appar.

* https://dbwebb.se/kunskap/javascript-argh-maste-jag
* https://dbwebb.se/kunskap/kom-igang-med-ramverket-mithril-for-javascript-spa



Flux, den underliggande arkitekturen {#flux}
-------------------------------

Det finns inget officiellt bibliotek för Flux, och det understyker den viktigaste lärdomen för dig som läser denna artikel - Flux är en arkitektur för enkelriktat dataflöde, precis som MVC är en arkitektur som tillåtter flerriktat dataflöde. Mithril använder MVC, men använder inte ett officiellt MVC-bibliotek som skaparna för MVC tillhandahåller. Facebook har dock ett officiellt Dispatcher bibliotek som ofta används, som du kan titta på [här](https://github.com/facebook/flux/blob/master/src/Dispatcher.js). Jag rekommenderar att du gör det, det är bara 250 rader kod i skrivande stund.

En populär implementation av Flux jag kommer täcka i en framtida artikel är Redux.

### Flux består av fyra huvudsakliga komponenter:
#### Dispatcher
Dispatchern är den centrala delen av en Fluxapp, och dess huvudsakliga jobb är att distribuera de actions som sker till alla stores. En store kan registrera sig hos dispatchern med en callback, och när en action sker kommer dispatchern att skicka den vidare till samtliga stores via den callback de har registrerat sig med. Det kan också beskrivas som en event-hanterare med fler regler.

Dispatchern kan utföra dessa callbacks i en förbestämd ordning - detta kan vara bra om en store är beroende av innehållet i en annan store, vilket är oundvikligt om man har en större applikation där du inte vill ha duplicerad data.

#### Store
Stores innehåller applikationens tillstånd (State) och även dess logik. En store kan liknas vid en modell i MVC, men lagrar data för en hel avdelning inom applikationen istället för att varje instans av modellen representerar ett resultat från en sökning, vilket ofta är fallet med en ORM.

En store registrerar sig själv hos dispatchern, och har internt en switch-sats som tolkar hur en mottagen action ska hanteras, och knyter ihop den med den interna logik som finns i din store. På detta sätt leder en action till att tillståndet i en store uppdateras. När detta sker sänder storen även ut ett event som säger att det nu har ett uppdaterat tillstånd, för att låta de vyer som beror på den uppdatera sig.

Kodexempel på en Store i Flux:
```javascript
var Store = {
    data: [],
    methodForThisData: function () {
        console.log(data);
    }
}
```

#### Actions
En dispatcher exponerar en metod som låter oss utlösa ett utskick till de stores som är registrerade, och vi inkluderar där en payload med data. Denna payload kallar vi en action. En action har även en type, som används inuti storen för att avgöra hur en action ska hanteras. En action kan komma från flera olika ställen. Några vanliga exempel är en server eller en view.

Kodexempel på en ActionCreator i Flux:
```javascript
function actionCreatorExample(blogPost) {
    var action = {
      actionType: "CREATE_BLOGPOST",
      blogPost: blogPost
    };

    Dispatcher.dispatch(action);
}
```

#### View
Vyer i Flux är samma sak som i MVC - det användaren ser och kan interagera med. Ofta ser man här React - ett bibliotek för att skapa vyer som är komposerbara och enkla att rendera om varje gång datan i våra Stores förändras. Denna data är ofta skickad till varje komponent i ett och samma JavaScript-objekt, och varje komponent tar den data som den behöver. Actions är knutna till vykomponenter, exempelvis knappar, och skickas till dispatchern baserat på användarens input.

##### Controller-View
Komponenterna i vyn är organiserade precis som ett vanligt domträd - och roten i Reacts domträd är en controller-view. Denna komponent har ansvaret för att hämta data från stores och distribuera den nedåt till sina barn i trädet. Det är inte ovanligt att varje "logisk enhet" på en hemsida har sin egen controller-view. Exempel på två logiska enheter är en chattfunktionalitet och en kundvagn.



#### Interaktionerna mellan komponenterna i Flux kan representeras grafiskt på det här sättet:

![picture alt](http://144.76.58.55/Flux.png "The flux architecture")

#### Vi kan också representera dem via ett fiktivt samtal:

**Vykomponent (React):** Öj, Action, någon klickade på Skapa Todo knappen!

**Action:** Okej React, jag registrerade en ActionCreator hos dispatchern, den kommer ta hand om att vidarebefodra den här informationen till alla stores som är intresserade av den.

**Dispatcher:** Hmmm... Har jag någon som bryr sig om en ny Todo? Ja, en store har registrerat en callback hos mig! Jag meddelar den.

**Store:** Okej dispatcher, tack för uppdateringen! Jag uppdaterar min data och signalerar ett event för Reactkomponenterna!

**React:** Nej men titta, en store jag är intresserad av har ny data! Dags att uppdatera gränssnittet med den nya datan!



Skillnaden mellan Flux och MVC {#fluxmvc}
-------------------------------

Den största skillnaden är att Flux har ett enkelriktat dataflöde, och MVC stödjer oftast ett tvåvägsdataflöde. Många duktiga ingenjörer hävdar dock att korrekta implementationer av MVC har ett enkeltriktat dataflöde, och att de som inte har det bör samlas under MVW-kategorin istället, där W står för "Whatever.". I verkligheten skiljer sig MVC-implementationer från varandra och fungerar olika. Flux väljer att röra sig bort från MV*-termen helt, och representerar en arkitektur som inte stödjer tvåvägsbindningar, oavsett man vill det eller inte. All data flödar enkelriktat, och alla förändringar går via dispatchern,

Enligt skaparna av Flux ser en typisk implementation av MVC på klientsidan av en webbapplikation ut ungefär såhär:

![picture alt](http://144.76.58.55/MVC.png "The MVC architecture")

Svårt att läsa på en bild, mycket svårare att läsa i kod. Problemet med den här typen av arkitektur är att det är svårt att felsöka, det är svårt att resonera kring det, och det är svårt att utveckla nya saker, oavsett om använder samma data eller inte, utan att introducera oförutsägbara och svårlösta buggar. En traditionell bugg i Facebooks chatt var att det ofta kom spöknotifikationer - ikonen visade att man hade ett meddelande som inte fanns. Detta problem fortsatte finnas kvar i facebook webbklient trots att det lösts flera gånger om, men när de ändrade den underliggande strukturen till att använda Flux eliminerade de buggen helt. Detta för att ett enkelriktat dataflöde undviker cirkulära databeroenden som kan skapa kaskadeffekter i din applikation. För att uppnå detta måste datalagret i flux alltid rendera klart innan nya actions accepteras eller triggas.



React, den totala abstraktionen {#react}
-------------------------------

React skapades av Jordan Walke, och är inspirerat av XHP, ett php-bibliotek som utvecklats på Facebook. XHP och React med JSX är inte fullfjädrade ramverk i sig själva, utan mer en abstraktion mellan språket och DOM-trädet som vi vill manipulera.

React representerar en TOTAL abstraktion från webbläsarens domträd - den enda gången du rör det är när du väljer i vilket element du vill att din REACT-app ska renderas. Därefter sköter React all interaktion med domträdet, och du arbetar i något som kallas en "Virtual DOM".

Den huvudsaklig anledningen till att React och dess Virtuella DOM existerar är för att snabba upp förändringen av domträdet när din applikations data förändras. När datan förändras gör React en diff mellan det virtuella trädet och webbläsarens träd, och utför minsta möjliga operationer för att göra webbläsarens träd likvärdigt med det virtuella trädet. Detta är ett snabbt sätt att uppdatera en sida, eftersom att alla komplicerade operationer sker i ren javascript, utan att man måste läsa till eller från domträdet.

Relationen mellan applikationen, det virtuella trädet och webbläsarens träd ser ut såhär:
![picture alt](http://144.76.58.55/VirtualDOM.png "React and it's virtual DOM")

En vinst utöver snabbheten när man abstraherar bort DOMträdet är att du inte behöver tänka HTML/JS/CSS längre, utan det räcker att tänka JavaScript och komponenter - att komponenterna sedan kan återanvändas är inte dumt. Exakt hur man ska styla sin React-app är ett väldigt omdebaterat område - vissa anser att man bör förlita sig helt på inline styling, och andra föredrar separata CSS-filer. Båda alternativ fungerar, och har för och nackdelar.



JSX, HTML-element i JavaScript {#jsx}
-------------------------------

JSX är ett markupspråk som kan användas tillsammans med React, det låter dig skriva dina komponenter med ett HTML-likt syntax istället för att använda React.createElement. Detta kräver dock att du använder babel med ett react-preset för att kompilera din kod - något vi kommer gå igenom i nästkommande artikel.

Vi behöver inte täcka JSX mycket mer i denna artikel - vinsten med det kommer visa sig när vi utvecklar med det. Här är ett exempel hur hur samma sak ser ut i React och JSX:

Denna JSX-kod:
```javascript
<LoginButton color="cyan">
  Logga in!
</LoginButton>
```
Motsvarar denna React-kod:
```javascript
React.createElement(
  LoginButton,
  {color: 'cyan'},
  'Logga in!'
)
```
JSX är lättare att läsa och arbeta med, eller vad säger du? Det är inte ett krav att använda JSX med React, men det är något jag starkt rekommenderar.



Babel och ES2015 med Webpack {#webpack}
-------------------------------

ES2015, ES6 eller helt enkelt den senaste versionen av JavaScript är vad du kommer att se när du läser den officiella dokumentationen från React och Flux - och även de flesta kodexempel du hittar online. Man kan argumentera till höger och vänster om det är dags att börja använda ES2015 eller inte, men jag tänker göra valet lätt för dig i denna tutorial - jag kommer bara skriva ES2015.

Min personliga motivation för att använda ES2015 är väldigt simpel - class-syntaxet och nyckelordet "let" gör att JavaScript blir mer likt andra språk jag utvecklar i. Jag ser mig som en programmerare, inte en "valfritt-språk-här" programmerare, och uppskattar därmed att kunna undvika språk-specifika saker i den mån det är rimligt. Imp

I nästa artikel kommer jag gå igenom hur man sätter upp en bra utvecklingsmiljö för React, JSX och ES2015 med Webpack och Babel - I denna artikel kommer vi därför förlita oss på den onlinekompilator som finns för att kunna arbeta med ES2015 och ge dig en smak på vad det innebär.

https://babeljs.io/repl/#?babili=false&evaluate=true&lineWrap=false&presets=es2015%2Creact%2Cstage-0&code=



Hello World med React och Flux {#helloworld}
-------------------------------

Jag kände att jag inte kunde lämna er utan ett kod-exempel, vi går därför igenom ett Hello World exempel som använder React och JSX med den Babel-kompilator jag länkade ovan. Vi lämnar Flux till senare då jag tänker att vi använder Redux-implementationen, och det blir för mycket att täcka den också i denna artikel.

Vi börjar med vår index.html, som har ett div-element med id "app". Det är här vi ska ankra vår React-app. Vi inkluderar också react och react-dom, samt vår egen bundle.js fil där vi kommer lägga den kompilerade koden.
```html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>React yo!</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.4.0/react.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.4.0/react-dom.js"></script>

    </head>
    <body>
        <div id="app"></div>
        <script src="bundle.js"></script>
    </body>
</html>

```
All kodning kommer nu ske i Babel-kompilatorn online, klistra sedan in resultatet i er bundle.js.

Vi börjar med att definiera en App-komponent med JSX
```javascript
class App extends React.Component {
    render() {
        return <p> Hello World! </p>;
    }
}
```
Och fortsätter med att ankra denna i vår app-div. Om du nu kopierar resultatet in till bundle.js borde du se Hello World!

```javascript
ReactDOM.render (
  <App />,
  document.getElementById("app")
);
```

Om du har tagit dig ända hit har du precis djupdykt in teorin och filosofin bakom Flux, React och JSX. Vi ses i nästa artikel då vi sätter uppp en utvecklingsmiljö för att bygga komplexa appar!
