class HomeworkModel {
  int? id;
  int? homeworkId;
  String? creatorName;
  String? name;
  String? description;
  int? duration;
  String? startDate;
  String? dueDate;
  String? type;
  bool? isSubmitted;

  HomeworkModel({
    required this.id,
    required this.homeworkId,
    required this.creatorName,
    required this.name,
    required this.description,
    required this.duration,
    required this.startDate,
    required this.dueDate,
    required this.type,
    required this.isSubmitted,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'homeworkId': homeworkId,
      'creatorName': creatorName,
      'name': name,
      'description': description,
      'duration': duration,
      'startDate': startDate,
      'dueDate': dueDate,
      'type': type,
      'isSubmitted': isSubmitted,
    };
  }

  HomeworkModel.fromMap(Map<String, dynamic> map) {
    id = map['id'];
    homeworkId = map['homeworkId'];
    creatorName = map['creatorName'];
    name = map['name'];
    description = map['description'];
    duration = map['duration'];
    startDate = map['startDate'];
    dueDate = map['dueDate'];
    type = map['type'];
    isSubmitted = map['isSubmitted'];
  }
}
