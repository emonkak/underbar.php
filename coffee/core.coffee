# Classes  {{{1

class ScrollSpy
  constructor: (container) ->
    @$container = $(container)
    @onScroll = _.throttle _.bind(@onScroll, this), 100
    @watches = []

  start: ->
    @$container.on 'scroll', @onScroll
    @onScroll()

  stop: ->
    @$container.off 'scroll', @onScroll

  observe: (el) ->
    @watches.push el

  contains: ($el, scrollTop, scrollBottom) ->
    offsetTop = $el.offset().top
    offsetBottom = offsetTop + $el.height()

    if offsetTop >= scrollTop && offsetBottom <= scrollBottom
      # Visible all region.
      true
    else if offsetTop <= scrollBottom
      visibleHeight = Math.min(offsetBottom, scrollBottom) - Math.max(offsetTop, scrollTop)
      halfContainerHeight = (scrollBottom - scrollTop) / 2
      visibleHeight >= halfContainerHeight
    else
      false

  onScroll: ->
    scrollTop = @$container.scrollTop()
    scrollBottom = scrollTop + @$container.height()

    for el in @watches
      hash = el.hash
      $el = $(el)
      $target = $(if hash.length > 1 then hash else 'body')
      continue if $target.length is 0

      if @contains($target, scrollTop, scrollBottom)
        unless $el.data 'inside'
          @onEnter.call this, el, $target[0] if @onEnter
          $el.data 'inside', true
      else if $el.data 'inside'
        @onLeave.call this, el, $target[0] if @onLeave
        $el.data 'inside', false

    return



# Variables  {{{1

isFocusToHeader = false
isSmoothScrolling = false
$header = $('#header').on
  mouseenter: -> isFocusToHeader = true
  mouseleave: -> isFocusToHeader = false




# Scroll Spy  {{{1

scrollSpy = new ScrollSpy(window)
scrollSpy.onEnter = (el) ->
  $active = $(el).parent().addClass('is-active')

  if !isFocusToHeader || isSmoothScrolling
    scrollTop = $header.scrollTop()
    offsetTop = $active.position().top
    height = $header.height()
    $header.stop().animate
      scrollTop: scrollTop + offsetTop - (height / 2)

scrollSpy.onLeave = (el) ->
  $(el).parent().removeClass('is-active')

# Register menu items to the scroll spy.
$('.menu a[href^="#"]').each ->
  scrollSpy.observe this

scrollSpy.start()



# Smooth scroll  {{{1

$(document).on 'click', 'a[href^="#"]', (e) ->
  e.preventDefault()
  $target = $(if @hash.length > 1 then @hash else 'body')

  if $target.length
    offsetTop = $target.offset().top
    difference = Math.abs(offsetTop - $(window).scrollTop())

    isSmoothScrolling = true
    duration = Math.min(difference, 500)
    $('html, body').stop().animate
      scrollTop: offsetTop
    , duration, 'swing', =>
      window.location.hash = @hash
      scrollSpy.onScroll()
      isSmoothScrolling = false

    false




# Add target=_blank   {{{1

$('a[href^="http://"], a[href^="https://"]').attr('target', '_blank')
