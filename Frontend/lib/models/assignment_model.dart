class AssignmentModel {
  int? id;
  String? name;
  String? description;
  String? level;
  double? totalScore;
  String? specialized;
  String? subject;
  String? topic;
  String? creator;
  bool? isCreator;
  String? createAt;
  int? totalQuestion;

  AssignmentModel({
    required this.id,
    required this.name,
    required this.description,
    required this.level,
    required this.totalScore,
    required this.specialized,
    required this.subject,
    required this.topic,
    required this.creator,
    required this.isCreator,
    required this.createAt,
    required this.totalQuestion,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'level': level,
      'totalScore': totalScore,
      'specialized': specialized,
      'subject': subject,
      'topic': topic,
      'creator': creator,
      'isCreator': isCreator,
      'createAt': createAt,
      'totalQuestion': totalQuestion,
    };
  }

  AssignmentModel.fromMap(Map<String, dynamic> map) {
    id = map['id'];
    name = map['name'];
    description = map['description'];
    level = map['level'];
    totalScore = map['totalScore'];
    specialized = map['specialized'];
    subject = map['subject'];
    topic = map['topic'];
    creator = map['creator'];
    isCreator = map['isCreator'];
    createAt = map['createdAt'];
    totalQuestion = map['numberOfQuestions'];
  }
}
