const Snippets = {
  tags: function ({ id, events, dataLayer, dataLayerName }) {
    if (!id) warn('GTM Id is required')
    
    const iframe = `
      <iframe src="//www.googletagmanager.com/ns.html?id=${id}"
        height="0" width="0" style="display:none;visibility:hidden" id="tag-manager"></iframe>`
  
    const script = `
      (function(w,d,s,l,i){w[l]=w[l]||[];
        w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js', ${JSON.stringify(events).slice(1, -1)}});
        var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
        j.defer=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;
        f.parentNode.insertBefore(j,f);
      })(window,document,'script','${dataLayerName}','${id}');`
  
    const dataLayerVar = this.dataLayer(dataLayer, dataLayerName)
  
    return {
      iframe,
      script,
      dataLayerVar
    }
  },
  dataLayer: function (dataLayer, dataLayerName) {
    return `
      window.${dataLayerName} = window.${dataLayerName} || [];
      window.${dataLayerName}.push(${JSON.stringify(dataLayer)})`
  }
}  

const TagManager = {
  dataScript: function (dataLayer) {
    const script = document.createElement('script')
    script.innerHTML = dataLayer
    return script
  },
  gtm: function (args) {
    const snippets = Snippets.tags(args)

    const noScript = () => {
      const noscript = document.createElement('noscript')
      noscript.innerHTML = snippets.iframe
      return noscript
    }

    const script = () => {
      const script = document.createElement('script')
      script.innerHTML = snippets.script
      return script
    }

    const dataScript = this.dataScript(snippets.dataLayerVar)

    return {
      noScript,
      script,
      dataScript
    }
  },
  initialize: function ({ gtmId, events = {}, dataLayer, dataLayerName = 'dataLayer' }) {
    const gtm = this.gtm({
      id: gtmId,
      events: events,
      dataLayer: dataLayer || null,
      dataLayerName: dataLayerName
    })
    if (dataLayer) document.head.appendChild(gtm.dataScript)
    document.head.appendChild(gtm.script())
    document.body.appendChild(gtm.noScript())
  },
  dataLayer: function ({dataLayer, dataLayerName = 'dataLayer'}) {
    const snippets = Snippets.dataLayer(dataLayer, dataLayerName)
    const dataScript = this.dataScript(snippets)
    document.head.appendChild(dataScript)
  }
}

module.exports = TagManager