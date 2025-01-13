class HomeworkModel {
  int? id;
  String? type;
  String? title;
  String? description;
  String? duration;
  String? startDate;
  String? dueDate;
  String? status;
  bool? isSubmitted;

  HomeworkModel({
    required this.id,
    required this.type,
    required this.title,
    required this.description,
    required this.duration,
    required this.startDate,
    required this.dueDate,
    required this.status,
    required this.isSubmitted,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'type': type,
      'title': title,
      'description': description,
      'duration': duration,
      'startDate': startDate,
      'dueDate': dueDate,
      'status': status,
      'isSubmitted': isSubmitted,
    };
  }

  HomeworkModel.fromMap(Map<String, dynamic> map) {
    id = map['id'];
    type = map['type'];
    title = map['title'];
    description = map['description'];
    duration = map['duration'];
    startDate = map['startDate'];
    dueDate = map['dueDate'];
    status = map['status'];
    isSubmitted = map['isSubmitted'];
  }
}
