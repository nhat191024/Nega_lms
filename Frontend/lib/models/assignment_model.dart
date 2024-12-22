class AssignmentModel {
  int? id;
  String? name;
  String? description;
  String? level;
  double? totalScore;
  String? specialized;
  String? subject;
  String? topic;

  AssignmentModel({
    required this.id,
    required this.name,
    required this.description,
    required this.level,
    required this.totalScore,
    required this.specialized,
    required this.subject,
    required this.topic,
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
  }
}
