$('.start-lesson').click(function(e) {
    $this = $(this);
    var lessonId = $this->attr('lesson-id');
    alert(lessonId);
});
