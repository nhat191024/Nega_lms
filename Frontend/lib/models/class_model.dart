class ClassModel {
  int? id;
  String? name;
  String? description;
  String? teacherName;
  List<String>? categories;
  String? createAt;

  ClassModel({
    required this.id,
    required this.name,
    required this.description,
    required this.teacherName,
    required this.createAt,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'teacherName': teacherName,
      'categories': categories,
      'createAt': createAt,
    };
  }

  ClassModel.fromMap(Map<String, dynamic> map) {
    id = map['id'];
    name = map['name'];
    description = map['description'];
    teacherName = map['teacherName'];
    categories = map['categories'];
    createAt = map['createAt'];
  }

  ClassModel.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    name = json['name'];
    description = json['description'];
    teacherName = json['teacherName'];
    categories = json['categories'].cast<String>();
    createAt = json['createAt'];
  }
}
