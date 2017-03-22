class SearchHeadForm
  # Search form in-the-header
  constructor: (form) ->
    # Form containing search field
    @formEl = form
    @formEl.addEventListener('submit', @onSearch)
    # Search field
    @inputEl = @formEl.querySelector('[data-search-input]')
    @inputEl.addEventListener 'input', debounce =>
      @onType()
    # Handle user leaving the interaction
    @inputEl.addEventListener('blur', @onBlur)
    # Handle user return after blur
    @inputEl.addEventListener('focus', @onType)
    @resultsView = null

    # Disable the search field until site search is ready
    if not window.siteSearch.isReady
      # TODO actually disable the field
      @normalPlaceholderText = @inputEl.placeholder
      @inputEl.placeholder = "Standby"
      window.siteSearch.readyCallback = @onReady

  onReady: (e) =>
    # Re
    @inputEl.placeholder = @normalPlaceholderText

  onSearch: (e) =>
    # Called when the search field is submitted (press enter)
    # SearchHeadResultView overrides the 'enter' button handler when a result is
    # selected.
    e.preventDefault()
    # Forward query to search page
    Turbolinks.visit("/search/?q=#{@inputEl.value}")

  onType: =>
    # Called (de-bounced) when the user types into the search field. Does a
    # search to yield instant results.
    q = @inputEl.value
    if window.siteSearch.isReady and q.length > 0
      # Search is ready and we have a query
      window.siteSearch.search(q, @onResult)
    else if q.length == 0 and @resultsView
      # User has cleared the search field, get rid of results
      @clearDown()

  onResult: (results) =>
    # Callback for search results
    if @resultsView
      # Result view already present, re-render it
      @resultsView.render(results)
    else
      # Create the result view and render it
      @resultsView = new SearchHeadResultsView(@)
      @resultsView.render(results)

  onBlur: =>
    # User has clicked away, remove results
    unless @resultsView == null
      @clearDown()

  clearDown: ->
    # Remove resultview
    if @resultsView
      @resultsView.remove()
      @resultsView = null


class SearchHeadResultsView
  # Results view, has many search results, handles dealing with those results
  SITE_SEARCH_CONTAINER = '#site-search'
  RESULT_MAX = 12
  constructor: (parent) ->
    # Store ref. to parent
    @parent = parent
    # Create templates needed for rendering the container of results
    @template = _.template(window.siteSearch.templateResultsHTML)
    @emptyTemplate = _.template(window.siteSearch.templateResultsEmptyHTML)
    @insert()
    # Bind keyboard events, we have set a class of 'mousetrap' on the search
    # input to allow us to listen to keyboard events.
    Mousetrap.bind('up', @onUp)
    Mousetrap.bind('down', @onDown)
    Mousetrap.bind('enter', @onSelect)

  insert: ->
    # Insert ourselves into the DOM, called on construction
    @containerDiv = document.createElement("div")
    @containerDiv.classList.add 'dynamic-search'
    @containerDiv.innerHTML = @template()
    # We are inserted into the same <div> that contains the site search so it
    # aligns nicely.
    document.querySelector(SITE_SEARCH_CONTAINER).appendChild(@containerDiv)
    # Set a reference to the <ul> for results
    @resultsListEl = @containerDiv.querySelector('#site-search__results')

  render: (results) ->
    # Delete all results currently in the list
    while (@resultsListEl.firstChild)
      @resultsListEl.removeChild(@resultsListEl.firstChild)

    # Contains all the child views (i.e. individual results)
    @resultViews = []
    # Index of the currently selected result
    @activeI = null

    if results.length > 0
      # We have results, render them. Limit the number of results shown
      for result in results.slice(0, RESULT_MAX)
        rV = new SearchHeadResultView(@, result)
        @resultsListEl.appendChild(rV.node)
        @resultViews.push(rV)
    else
      # No results, render the empty template
      @resultsListEl.innerHTML = @emptyTemplate()

  setActive: (item) ->
    # Set the result 'item' as the active result (moused-over or selected via
    # the keyboard)
    if @resultViews.indexOf(item) == @activeI
      return # Skip if same
    if @activeI != null
      @resultViews[@activeI].deactivate()
    @activeI = @resultViews.indexOf(item)
    @resultViews[@activeI].activate()

  onUp: (e) =>
    # User moved selection up in the results list
    e.preventDefault() # Stop moving the cursor in the text field
    if @resultViews.length > 0 # Ensure there are actually results to select
      unless @activeI == null
        # Go up one, within limits
        view = @resultViews[Math.max(0, @activeI-1)]
        @setActive(view)

  onDown: (e) =>
    # User moved selection down in the results list
    e.preventDefault() # Stop moving the cursor in the text field
    if @resultViews.length > 0 # Ensure there are actually results to select
      if @activeI == null
        # Nothing selected, choose the first
        view = @resultViews[0]
      else
        # Go down one, within limits
        view = @resultViews[Math.min(@resultViews.length-1, @activeI+1)]
      @setActive(view)

  onSelect: (e) =>
    # User selected a result (pressing enter &c)
    unless @activeI == null
      e.preventDefault() # Don't submit the form
      @resultViews[@activeI].go() # Navigate

  remove: ->
    # Destroy the view
    document.querySelector(SITE_SEARCH_CONTAINER).removeChild(@containerDiv)
    Mousetrap.unbind('up')
    Mousetrap.unbind('down')
    Mousetrap.unbind('enter')


class SearchHeadResultView
  # An individual search result, child of SearchHeadResultsView
  CLASS_LI = 'dynamic-search__result'
  CLASS_ACTIVE = '--active'
  constructor: (parent, result) ->
    @parent = parent
    @result = result
    @template = _.template(window.siteSearch.templateResultHTML)
    @render()

  render: ->
    # This view is only rendered once. Setup @node. We do not insert @node into
    # the DOM, that's the responsibility of this view's parent.
    @node = document.createElement('li')
    @node.classList.add CLASS_LI
    @node.innerHTML = @template
      item: @result
    @anchorEl = @node.querySelector('a')
    @anchorEl.addEventListener('mousedown', @onClick)
    @anchorEl.addEventListener 'mouseenter', =>
      @parent.setActive(@)

  activate: ->
    # Activate (mouseover, keyboard ) the result
    @anchorEl.classList.add CLASS_ACTIVE

  deactivate: ->
    # Reverse @activate()
    @anchorEl.classList.remove CLASS_ACTIVE

  onClick: (e) =>
    # Prevents blurring of the search field which would destroy the results view
    # when user tries to click on a result. The mousedown causes blurring which
    # destroys the results before the mouseup can trigger the nav. We get
    # unclickable links :'(
    e.preventDefault()

  go: ->
    # Navigate to this result.
    Turbolinks.visit(@anchorEl.href)


# On every page nav
document.addEventListener 'turbolinks:load', ->
  searchForm = document.querySelector('#site-search-header')
  if searchForm
    window.searchHeadForm = new SearchHeadForm(searchForm)

document.addEventListener 'turbolinks:visit', ->
  if window.searchHeadForm
    window.searchHeadForm.clearDown()
