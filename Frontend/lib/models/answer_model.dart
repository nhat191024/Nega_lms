class AnswerModel {
  int? questionId;
  int? choiceId;

  AnswerModel({
    this.questionId,
    this.choiceId,
  });

  Map<String, dynamic> toMap() {
    return {
      'question_id': questionId,
      'choice_id': choiceId,
    };
  }

  AnswerModel.fromMap(Map<String, dynamic> map) {
    questionId = map['question_id'];
    choiceId = map['choice_id'];
  }
}
