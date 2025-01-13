class Choice {
  int? id;
  String? choice;
  bool isChoose;

  Choice({
    this.id,
    this.choice,
    this.isChoose = false,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'content': choice,
      'is_choose': isChoose,
    };
  }

  factory Choice.fromMap(Map<String, dynamic> map) {
    return Choice(
      id: map['id'],
      choice: map['choice'],
      isChoose: map['is_choose'] ?? false,
    );
  }
}

class QuestionModel {
  int? id;
  String? question;
  List<Choice>? choices;

  QuestionModel({
    required this.id,
    required this.question,
    this.choices,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'question': question,
      'choices': choices?.map((x) => x.toMap()).toList(),
    };
  }

  QuestionModel.fromMap(Map<String, dynamic> map) {
    id = map['id'];
    question = map['question'];
    choices = List<Choice>.from(map['choices']?.map((x) => Choice.fromMap(x)) ?? []);
  }
}
