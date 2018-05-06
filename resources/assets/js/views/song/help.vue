<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-6 col-12 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">{{trans('song.help')}}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><router-link to="/home">{{trans('general.home')}}</router-link></li>
          <li class="breadcrumb-item"><router-link to="/song">{{trans('song.song')}}</router-link></li>
          <li class="breadcrumb-item active">{{trans('song.help')}}</li>
        </ol>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <h2>Skrive sanger i .chordpro format</h2>
            <p>
              Sangbasen bruker <a href="https://www.chordpro.org/chordpro/index.html" target="_blank">.chordpro</a> formatet for å generere flotte akkordskjemaer.
              Fordelene med dette formatet er at sangen kan transponeres med korrekte
              intervaller, og at filen kan lastes ned og åpnes i programmer som <a href="https://onsongapp.com" target="_blank">OnSong</a>.
              Du kan også forhåndsvise hvordan sangen din ser ut på <a href="http://onlinesong.herokuapp.com/" target="_blank">denne siden</a>.
            </p>
            <h4 class="mt-4">Akkorder</h4>
            <p>
              Akkordene skrives i braketter på samme linje som teksten, som dette:
            </p>
            <p>
              <samp>[Db]Når regnet sler imot deg</samp>
            </p>
            <p>
              På tastaturet får du braketter med alt+parantes. Da vil systemet oppfatte innholdet i braketten som en akkord, og vise den i riktig format.
              Husk å bruke engelsk versjon av H-akkorder. f.eks <samp>[Bm7], [B/F#]</samp> osv.
            </p>
            <p>
              Akkorder skrives på samme linje som teksten, og hver akkord skrives foran ordet eller stavelsen den kommer på.
            </p>
            <p>
              <samp>
                [Db]Når regnet sler imot deg, [Ab/C]der du går<br>
                [Abm/B]og når du ikkje veit di [Gb/Bb]arme råd
              </samp>
            </p>
            <h4 class="mt-4">Tilgjengelige akkorder</h4>
            <p>
              Her er akkorder som systemet kjenner igjen. Hver av dem kan også kombineres med skråstrek og basstone, f.eks. <samp>[C/E]</samp>.
              <p>
                <samp>
                  <span>[C]</span>
                  <span>[Cadd9]</span>
                  <span>[C2]</span>
                  <span>[Cadd11]</span>
                  <span>[C4]</span>
                  <span>[C6]</span>
                  <span>[C7]</span>
                  <span>[C9]</span>
                  <span>[Cmaj7]</span>
                  <span>[Cm7]</span>
                  <span>[C7]</span>
                  <span>[Cdim7]</span>
                  <span>[Cmaj9]</span>
                  <span>[Cmaj11]</span>
                  <span>[Cmaj13]</span>
                  <span>[C7#5]</span>
                  <span>[Cmaj7#5]</span>
                  <span>[Cm7add9]</span>
                  <span>[Cmin9]</span>
                  <span>[Caug]</span>
                  <span>[C+]</span>
                  <span>[Cdim]</span>
                </samp>
              </p>

            </p>
            <h4 class="mt-4">Sangdeler (intro, vers, refreng, etc)</h4>
            <p>
              For å ha en god struktur i akkordskjemaet, begynn hver sangdel med <samp>Intro, Vers, Refreng</samp> etc, etterfølgt av kolon på en egen linje før teksten.
              Ved å følge denne strukturen vil akkordskjemaet bli kompatibelt med <a href="https://onsongapp.com" target="_blank">OnSong</a> og <a href="https://easyworship.com" target="_blank">EasyWorship</a>.
            </p>
            <p>
              <samp>
                Vers 2:<br>
                [Db]Når alle nattas stjerner [Ab/C]viser seg<br>
                [Abm/B]og ingen er der for å [Gb/Bb]trøste deg<br>
                [Gbm/A]då vil du alltid ha ein [Db/Ab]heim hjå meg<br>
                [Eb]fordi eg [Ab] elskar [Db]deg<br><br>
                Refreng:<br>
                [Gb]Eg veit du ikkje har best[Db/F]emt deg, men<br>
                [F7/A]eg lar deg ta den tid du [Bbm]vil<br>
                [Gb]for eg forstod heilt frå beg[Db/F]ynnelsen<br>
                det [Eb]er hjå meg du høyrer [Ab]til
              </samp>
            </p>

            <h4 class="mt-4">Finne Spotify-URI</h4>
            <p>
              Vil du ha en Spotify-spiller som spiller sangen sammen med notene, kan du kopiere inn Spotify-URI. Den finner du ved å høyreklikke på
              sangen i Spotify, gå til "Share" og velge "Copy Spotify URI". Lim deretter inn koden i skjemaet når du legger til sangen.
            </p>
            <p>
              <img src="/images/spotify-uri.jpg" alt="">
            </p>

            <h4 class="mt-4">Sette varighet</h4>
            <p>
              Ved å sette varighet (i minutter og sekunder) vil <a href="https://onsongapp.com" target="_blank">OnSong</a> kunne scrolle automatisk.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
